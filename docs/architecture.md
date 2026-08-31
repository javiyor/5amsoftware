# Arquitectura

## Principios

- Laravel estándar + capa de `Services` solo para reglas de negocio reutilizables.
- **`SubscriptionStatusService` es la única autoridad** para el estado financiero del contrato (ACTIVE/OVERDUE/SUSPENDED/CANCELLED). Ni vistas, ni API, ni scheduler calculan ese estado por su cuenta.
- Dinero en `decimal(15,2)`; nunca float.
- Fechas con Carbon en timezone de aplicación (`America/Argentina/Cordoba`); formato de visualización `d/m/Y`.
- Transacciones en operaciones financieras; `lockForUpdate()` en secciones críticas (webhook vs scheduler).
- Idempotencia mediante índices `unique` (payment_installments, payments provider+id, installments por ciclo, payment_transactions por evento).
- Pagos manuales y online comparten el mismo `PaymentService`/`PaymentAllocationService`.
- Mass assignment controlado por `$fillable` (atributos PHP de Laravel 13) — nunca `unguard()` global.
- Autorización en backend vía Policies/Gates; nunca confiar solo en ocultar botones.

## Estructura de carpetas

```
app/
  Console/            → Commands de facturación y licencias
  Enums/              → UserRole, CustomerStatus, DemoRequestStatus, CustomerSystemStatus,
                        LicenseStatus, InstallmentStatus, PaymentStatus
  Events/             → PaymentApproved, ContractSuspended, ContractReactivated,
                        InstallmentGenerated, DemoRequested
  Listeners/
  Http/
    Controllers/Admin/ Auth/ Client/ Public/ Api/
    Middleware/
    Requests/
  Jobs/
  Mail/
  Models/
  Notifications/
    Channels/         → EmailNotificationChannel, DatabaseNotificationChannel,
                        NotificationChannelInterface (+ preparación WhatsApp)
  Policies/
  Services/
    Billing/          → BillingService, InstallmentGenerationService, SubscriptionStatusService,
                        SuspensionService, PaymentService, PaymentAllocationService
    Licensing/        → LicenseService
    Payments/         → PaymentGatewayInterface, MercadoPagoPaymentGateway
    Customers/        → DemoRequestService
    Support/          → AuditService, CompanySettingsService

routes/
  web.php             → landing, admin, cliente, auth
  api.php             → /api/v1/license/verify
  console.php         → tareas programadas + comandos

resources/views/
  layouts/ landing/ auth/ admin/ client/ emails/ errors/
```

## Reglas de negocio clave

1. **Vencimiento:** una cuota pasa a OVERDUE el primer día completo después de `due_at`.
2. **Atraso:** `daysOverdue = max(0, now.startOfDay().diffInDays(due_at.startOfDay()))`.
3. **Gracia:** suspensión elegible **solo** si `daysOverdue > grace_days` (10) → primera suspensión al 11.º día completo (p. ej. día 12 para vencimiento el 01/08). `grace_override_until` extiende la fecha tope.
4. **Ciclos:** `next_billing_at += billing_interval_days` (30 días exactos, sin deriva).
5. **Suspensión manual** (`manual_suspension = true`) persiste aunque el cliente pague; solo un admin puede levantarla.
6. **Reactivación:** solo si no queda deuda bloqueante (otra cuota > gracia mantiene SUSPENDED).

## Seguridad

- CSRF, escaping Blade (nunca `{!! !!}` con datos no confiables), rate limiting en login/forms/webhook/API.
- Uploads validados (MIME/extensión/tamaño), nombres aleatorios, sin ejecución de PHP.
- Webhooks validados por firma y verificación server-to-server; nunca se acredita un pago solo por el payload o por el retorno del navegador.
- `APP_DEBUG=false` en producción; errores paginados con branding (403/404/419/429/500/503).
- Tokens sensibles (API de licencia) guardados hasheados; logs de API sin secretos completos.

## Colas

- Emails y notificaciones siempre por cola (`QUEUE_CONNECTION=database`).
- El estado financiero se persiste primero; las notificaciones se despachan después. La consistencia financiera no depende de la cola.
