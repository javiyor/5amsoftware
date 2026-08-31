# Base de datos

Convenciones: IDs `bigint`, FKs con índices, `timestamps()` y `softDeletes()` donde corresponde, importes `decimal(15,2)`, fechas `timestamp` en timezone UTC/DB. Códigos amigables (cliente, contrato, pago) en columnas con índice `unique` pero **nunca** como PK.

## Tablas

### users
`id, name, email unique, email_verified_at, password, is_active bool default true, must_change_password bool default false, last_login_at nullable, remember_token, created_at, updated_at`

### roles
`id, name, slug unique, created_at, updated_at`
Pivote `user_role`: `user_id`, `role_id` (unique compuesto).

### login_logs
`id, user_id nullable, email nullable, success bool, ip, user_agent, created_at`

### customers
`id, user_id nullable, code unique, first_name, last_name, company_name, business_name, tax_id, email, phone, whatsapp, address, city, state, country default 'Argentina', postal_code, status, notes, registered_at, created_at, updated_at, deleted_at`

### systems
`id, name, slug unique, short_description, description longtext, main_image, price_from, installation_price, is_featured, is_published, sort_order, seo_title, seo_description, created_at, updated_at, deleted_at`

### system_features
`id, system_id FK, name, description nullable, sort_order, timestamps`

### system_plans
`id, system_id FK, name, slug, description, price decimal, setup_price decimal nullable, billing_interval_days default 30, grace_days default 10, max_users nullable, is_active, sort_order, timestamps, deleted_at`
Unique: `(system_id, slug)`.

### customer_systems (contrato)
`id, customer_id, system_id, system_plan_id nullable, contract_number unique, custom_name, started_at, activated_at, billing_started_at, next_billing_at, billing_interval_days default 30, grace_days default 10, monthly_amount decimal, setup_amount decimal default 0, currency default 'ARS', automatic_billing bool default true, automatic_suspension bool default true, status, manual_suspension bool default false, manual_suspension_reason nullable, grace_override_until nullable, domain, installation_id, notes, cancelled_at, timestamps, deleted_at`
Índices: `customer_id`, `system_id`, `status`, `next_billing_at`.

### licenses
`id, customer_system_id unique, license_key unique, api_token_hash nullable, status, activated_at, expires_at, last_check_at, last_ip, domain, installation_id, metadata json, timestamps`

### installments
`id, customer_system_id, customer_id, number, period_start, period_end, issued_at, due_at, subtotal decimal, discount_amount decimal default 0, interest_amount decimal default 0, total decimal, status, paid_amount decimal default 0, paid_at nullable, notes nullable, timestamps, deleted_at`
Unique: `(customer_system_id, period_start, period_end)` — idempotencia de generación.
Índices: `status`, `due_at`, `customer_id`.

### payments
`id, customer_id, payment_number unique, payment_method, amount decimal, currency default 'ARS', status, paid_at nullable, provider nullable, provider_payment_id nullable, provider_preference_id nullable, provider_external_reference nullable, raw_response json nullable, notes nullable, created_by nullable, timestamps`
Unique: `(provider, provider_payment_id)` cuando provider_payment_id no es NULL.

### payment_installments
`id, payment_id, installment_id, amount decimal, timestamps`
Unique: `(payment_id, installment_id)`.

### payment_transactions
`id, payment_id nullable, provider, event_type, provider_event_id nullable, provider_payment_id nullable, status nullable, payload json, processed_at nullable, processing_error nullable, timestamps`
Unique: `(provider, event_type, provider_event_id)`.

### demo_requests
`id, system_id nullable, first_name, last_name, company_name nullable, email, phone nullable, whatsapp nullable, city nullable, number_of_users nullable, preferred_date nullable, preferred_time nullable, comments nullable, status, assigned_to nullable, converted_customer_id nullable, timestamps`

### demo_request_logs
`id, demo_request_id, user_id nullable, old_status nullable, new_status nullable, comment nullable, created_at`

### notification_logs
`id, notifiable_type, notifiable_id, notification_type, channel, sent_at, data json nullable, timestamps`
Unique: `(notifiable_type, notifiable_id, notification_type, channel, sent_at)` — anti-reenvío.

### audit_logs
`id, user_id nullable, action, entity_type, entity_id nullable, old_values json nullable, new_values json nullable, ip nullable, user_agent nullable, created_at`

### api_logs
`id, license_id nullable, endpoint, method, ip, user_agent, response_status, result, created_at`

### settings
Clave/valor: `key unique, value json nullable, group nullable, is_public bool, created_at, updated_at`
Claves iniciales: `default_billing_interval_days=30`, `default_grace_days=10`, `automatic_installment_generation=true`, `automatic_suspension=true`, `automatic_reactivation=true`, `days_before_due_notification=5`, `overdue_notification_days=5`, `pre_suspension_notification_days=9`, `company_timezone`, `currency=ARS`.

### company_settings
Configuración de empresa: `key unique, value json, group, created_at, updated_at`.
Grupos: `identity` (nombre, razón social, CUIT, direcciones, contactos), `branding` (logo, logo oscuro, favicon, colores, footer), `social` (Instagram/Facebook/LinkedIn), `billing` (datos bancarios), `legal` (términos, privacidad). Las rutas de archivos se guardan como strings; no se almacenan binarios en DB.
