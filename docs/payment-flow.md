# Flujo de pagos

## Roles

- `PaymentGatewayInterface` — contrato: `createCheckout()`, `getPayment()`, `processWebhook()`, `refund()`.
- `MercadoPagoPaymentGateway` — implementación con el SDK oficial (`mercadopago/dx-php` ^3.12, `PreferenceClient`).
- `PaymentService` — orquesta pagos manuales y online; única lógica financiera.
- `PaymentAllocationService` — imputa un pago a una o más cuotas vía `payment_installments`.

## Pago online (Checkout Pro)

1. Cliente elige cuota en el portal → validar usuario + propiedad + estado (`PENDING`/`OVERDUE`, no pagada/anulada).
2. Crear `Payment` local `PENDING` con `payment_number` único; `external_reference = payment_number` (identificador interno seguro).
3. `createCheckout()`: preferencia backend con items (importe **siempre desde la base de datos**, nunca del frontend), payer, back_urls `success/pending/failure`, `auto_return`, `statement_descriptor`.
4. Guardar `provider_preference_id`; redirigir al cliente a `init_point`.

**Las URLs de retorno son solo informativas.** El navegador puede ser manipulado: jamás confirman un pago.

## Webhook Mercado Pago

`POST /webhooks/mercadopago` — pública, sin auth web, con validación de firma oficial (el payload solo porta `data.id`) y rate limit.

Proceso:

1. Validar firma/origen del webhook (mecanismo oficial vigente de MP).
2. Consultar el pago al proveedor con `getPayment()` (server-to-server).
3. Comparar: `payment.id`, `external_reference` (debe ser nuestro `payment_number`), importe, currency, estado (`approved`).
   - Cualquier discrepancia → incidente en `payment_transactions` + auditoría; **no acreditar**.
4. Idempotencia: `DB::transaction` con `lockForUpdate()`; si `(provider, provider_payment_id)` ya fue procesado → responder OK sin duplicar.
5. Actualizar `Payment` (APPROVED, `paid_at`), imputar cuota(s) (`payment_installments`), recalcular cuotas y contrato, evaluar reactivación.
6. Registrar auditoría y despachar notificación (cola) **después** de persistir el estado.

## Pago manual

Admin registra efectivo/transferencia/depósito/tarjeta/otro con importe, fecha, referencia, observaciones y comprobante opcional. Se procesa con el **mismo** `PaymentService`; se requiere motivo/auditoría.

## Idempotencia

- `payments` unique `(provider, provider_payment_id)`.
- `payment_transactions` unique `(provider, event_type, provider_event_id)`.
- `payment_installments` unique `(payment_id, installment_id)`.

## Comprobantes

Imprimible + PDF (librería compatible con la versión de Laravel): logo, empresa, CUIT, cliente, número de pago, cuotas imputadas, sistema, fecha, método, importe, ID de transacción.
