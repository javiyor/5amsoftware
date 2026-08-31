# Reglas de facturación

## Convención de vencimiento y atraso (autoridad única)

La regla está centralizada en `SubscriptionStatusService` y en `Installment::daysOverdue()`.
Ni controladores, ni vistas, ni la API, ni el scheduler pueden duplicarla.

### 1. Momento de vencimiento

Una cuota con `due_at` deja de estar al día el **primer día completo posterior a su fecha de vencimiento** según la convención del sistema (fecha de vencimiento = fin del día de `due_at` en timezone de la aplicación).

- `due_at = 01/08` → el 01/08 la cuota es `PENDING` (no está vencida).
- El 02/08 la cuota pasa a `OVERDUE` (primer día completo transcurrido).

### 2. Cálculo de días de atraso

```
daysOverdue = max(0, now.startOfDay().diffInDays(due_at.startOfDay()))
```

Tabla de ejemplo con `due_at = 01/08`:

| Fecha     | daysOverdue | Estado regla            |
|-----------|-------------|-------------------------|
| 01/08     | 0           | PENDING (no vencida)    |
| 02/08     | 1           | OVERDUE (en gracia)     |
| 10/08     | 9           | OVERDUE (en gracia)     |
| 11/08     | 10         | OVERDUE (NO suspendida) |
| 12/08     | 11         | SUSPENDED (elegible)    |

### 3. Período de gracia y suspensión

- `grace_days` por defecto = **10**.
- **Suspensión elegible solo cuando `daysOverdue > grace_days`** (estrictamente mayor).
  - `>= 10` con gracia 10 **NO** suspende.
  - `> 10` (es decir, desde 11) habilita suspensión.
- Si `grace_override_until` está definido y es futuro, la suspensión queda inhabilitada hasta esa fecha (gracia extraordinaria, solo admin, con motivo y auditoría).
- Si `automatic_suspension = false`, el contrato se mantiene `OVERDUE` y **nunca** se suspende automáticamente.

### 4. Ciclos de 30 días exactos

- `billing_interval_days` por defecto = 30.
- `next_billing_at = next_billing_at + billing_interval_days` (incremento sobre el valor anterior, **nunca** `now() + 30`), evitando deriva si el scheduler corre tarde.
- La generación procesa contratos con `next_billing_at <= now` en un `while` con tope defensivo (máx. 60 ciclos) para reconstruir ciclos perdidos (ej. servidor apagado 90 días) sin loops infinitos.
- Cada cuota: `period_start` = inicio del ciclo, `period_end` = `next_billing_at`, `due_at` = `period_end`, `number` = ordinal del ciclo desde `billing_started_at`.

### 5. Estados del contrato (`CustomerSystemStatus`)

`PENDING | ACTIVE | OVERDUE | SUSPENDED | CANCELLED` (+ `DEMO` para evaluaciones).

`SubscriptionStatusService::evaluate(CustomerSystem)` orden de evaluación:

1. `status = CANCELLED` → `CANCELLED` (con `cancelled_at`).
2. `manual_suspension = true` → `SUSPENDED` (persiste aunque pague; solo admin la levanta).
3. Existe deuda bloqueante (`daysOverdue > grace` con `automatic_suspension = true` y sin `grace_override_until` vigente) → `SUSPENDED`.
4. Existe cuota `OVERDUE` → `OVERDUE` (dentro de gracia).
5. Sin deuda → `ACTIVE`.

### 6. Deuda bloqueante y reactivación

- Deuda bloqueante = cuota(s) con `daysOverdue > grace_days`.
- Al aprobarse un pago: imputar → recalcular cuotas → recalcular contrato.
- Si **queda** alguna cuota bloqueante → permanece `SUSPENDED`.
- Si **no queda** deuda bloqueante → `ACTIVE` y licencia `ACTIVE`.
- La suspensión manual nunca se levanta automáticamente por un pago.

### 7. Comandos (orden diario del scheduler)

1. `billing:generate-installments` — crea cuotas por ciclo vencido (idempotente por unique `(customer_system_id, period_start, period_end)`).
2. `billing:update-overdue` — `PENDING → OVERDUE` cuando pasó `due_at`; re-evalúa contratos.
3. `billing:evaluate-suspensions` — suspende vía `SuspensionService`, actualiza licencia, notifica una sola vez.
4. `billing:send-reminders` — avisos a 5 días previos, vencimiento, 5 y 9 días de atraso (anti-duplicado con `notification_logs`).
5. `licenses:evaluate` — resincroniza licencias con el estado de sus contratos.
