# API de licencias

## Endpoint

`POST /api/v1/license/verify`

Preferimos POST para no exponer claves en query strings ni logs.

### Request

```json
{
    "license_key": "A7DX-98LK-PQ42-M7KZ",
    "installation_id": "inst-abc123",
    "domain": "cliente.com.ar"
}
```

## Respuestas

### Licencia y suscripción activas

```json
{
    "success": true,
    "data": {
        "license_status": "active",
        "subscription_status": "active",
        "valid": true,
        "checked_at": "2026-08-11T20:00:00-03:00",
        "next_payment_due": "2026-09-10"
    }
}
```

### Suspendida

```json
{
    "success": true,
    "data": {
        "license_status": "suspended",
        "subscription_status": "suspended",
        "valid": false,
        "reason": "subscription_overdue",
        "message": "El servicio se encuentra suspendido."
    }
}
```

No se exponen datos financieros sensibles.

## Seguridad

- Autenticación: Sanctum con token por sistema instalado (el token se guarda **hasheado** en `licenses.api_token_hash`).
- `installation_id` obligatorio y registrado en `last_installation_id`/`last_ip`/`last_check_at`.
- `domain` validado si la licencia tiene dominio configurado.
- Rate limiting dedicado (p. ej. 60/min) + logging en `api_logs` (tokens enmascarados, sin secretos completos).
- HTTPS obligatorio en producción.
- **La autoridad es el servidor central.** El sistema remoto no "se auto-declara activo"; la API responde según DB + `SubscriptionStatusService` + `LicenseService`.

## Ciclo de vida

`PENDING → ACTIVE` (al activar contrato) → `SUSPENDED` (suspensión) → `ACTIVE` (reactivación) → `REVOKED` (baja) / `EXPIRED` (fin de demo). La clave usa `random_bytes()` con entropía suficiente (nunca `customer_id + system_id`).
