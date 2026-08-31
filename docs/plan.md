# Plan Maestro — Plataforma SaaS de venta, licencias y abonos

## Objetivo

Plataforma central para una empresa que comercializa sistemas informáticos: landing, portales (admin + cliente), contratación de sistemas, generación de cuotas cada 30 días, período de gracia, suspensión/reintegración automática, pagos online (Mercado Pago) y manuales, y API de validación de licencias.

## Stack

- Laravel 13.x (13.25.0) · PHP 8.3 · MariaDB/MySQL
- Blade + Bootstrap 5 + Vite + JavaScript Vanilla + Fetch API
- Eloquent, Form Requests, Policies/Gates, Scheduler, Queues, Notifications, Mail, Events
- Sanctum (API licencias), PHPUnit + Pest (tests)
- Mercado Pago Checkout Pro (`mercadopago/dx-php` ^3.12)

## Entidades principales

`users`, `roles`, `user_role`, `login_logs`, `customers`, `systems`, `system_features`, `system_plans`, `customer_systems` (contrato), `licenses`, `installments`, `payments`, `payment_installments`, `payment_transactions`, `demo_requests`, `demo_request_logs`, `notification_logs`, `audit_logs`, `api_logs`, `settings`, `company_settings`.

## Flujo de facturación

1. `billing:generate-installments` genera ciclos de `next_billing_at` (siempre `+ billing_interval_days`, nunca `now()+30`).
2. `billing:update-overdue` marca `PENDING → OVERDUE` cuando pasó `due_at`.
3. `billing:evaluate-suspensions` usa `SubscriptionStatusService` y suspende cuando `daysOverdue > graceDays`.
4. Pago (online/manual) → `PaymentService` → imputación → recalcular → reactivar si no queda deuda bloqueante.
5. `billing:send-reminders` emite avisos (5 días antes, vencimiento, 5/9 días de atraso) con `notification_logs` anti-duplicado.

## Flujo Mercado Pago

Checkout Pro con preferencia backend → `external_reference = payment_number` → webhook validado por firma → `getPayment()` server-to-server → comparación (id, external_reference, importe, currency, estado) → transacción idempotente → acreditación.

## Flujo de licencias

`POST /api/v1/license/verify` con `license_key` + `installation_id` (+ domain). Autoridad: DB + `SubscriptionStatusService` + `LicenseService`.

## Fases

0. Toolchain (PHP/Composer/MariaDB) — COMPLETADA
1. Base: env, DB, Bootstrap, layouts, auth, roles, estructura — COMPLETADA
2. Migraciones y modelos de dominio — COMPLETADA
3. Landing + CMS + demos + SEO — COMPLETADA
4. Admin: dashboard, clientes, sistemas, planes, contratos, demos — COMPLETADA
5. Billing: cuotas, gracia, suspensión, reactivación (tests exhaustivos)
6. Portal cliente
7. Mercado Pago (Checkout Pro, webhooks, idempotencia)
8. API de licencias
9. Emails, notificaciones, scheduler, queues
10. Auditoría, seguridad, optimización, README, deploy

## Prioridades

Integridad financiera > Seguridad > Suspensión/reactivación correcta > Idempotencia > Auditoría > Mantenibilidad > UX > Performance > Apariencia.

## Herramientas de la estación de trabajo

- PHP 8.3.33 (C:\php83), Composer 2.10.2 (C:\php83\composer.phar)
- MariaDB 12.3.2 en 127.0.0.1:3307, BD `5amsoftware`, usuario `laravel` (solo dev)
- Node 24 / npm (Bootstrap 5, Sass)
- Comandos: `php artisan test`, `composer test`, `npm run build`
