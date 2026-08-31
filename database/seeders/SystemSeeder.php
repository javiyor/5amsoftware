<?php

namespace Database\Seeders;

use App\Models\System;
use App\Models\SystemFeature;
use App\Models\SystemPlan;
use Illuminate\Database\Seeder;

class SystemSeeder extends Seeder
{
    public function run(): void
    {
        $systems = [
            [
                'name' => 'Gestión Comercial',
                'slug' => 'gestion-comercial',
                'short_description' => 'Ventas, facturación, stock y clientes en un solo lugar.',
                'description' => 'Sistema integral para gestionar todo el ciclo comercial de tu empresa: presupuestos, ventas, facturación electrónica, control de stock, cuentas corrientes y reportes gerenciales.',
                'price_from' => 45000,
                'installation_price' => 25000,
                'is_featured' => true,
                'is_published' => true,
                'features' => [
                    'Facturación electrónica AFIP',
                    'Control de stock y depósitos',
                    'Cuentas corrientes de clientes',
                    'Reportes y métricas de ventas',
                ],
                'plans' => [
                    ['name' => 'Inicial', 'slug' => 'inicial', 'price' => 45000, 'setup_price' => 25000, 'max_users' => 5],
                    ['name' => 'Profesional', 'slug' => 'profesional', 'price' => 70000, 'setup_price' => 35000, 'max_users' => 15],
                    ['name' => 'Corporativo', 'slug' => 'corporativo', 'price' => 110000, 'setup_price' => 50000, 'max_users' => null],
                ],
            ],
            [
                'name' => 'CRM Comercial',
                'slug' => 'crm-comercial',
                'short_description' => 'Seguimiento de oportunidades y equipos de ventas.',
                'description' => 'Organizá tus contactos, oportunidades y embudos de venta. Automatizá recordatorios, actividades y seguimiento para que ninguna oportunidad se escape.',
                'price_from' => 30000,
                'installation_price' => 20000,
                'is_featured' => false,
                'is_published' => true,
                'features' => [
                    'Embudo de ventas visual',
                    'Calendario y recordatorios',
                    'Base de contactos y empresas',
                    'Informes de rendimiento del equipo',
                ],
                'plans' => [
                    ['name' => 'Inicial', 'slug' => 'inicial', 'price' => 30000, 'setup_price' => 20000, 'max_users' => 5],
                    ['name' => 'Profesional', 'slug' => 'profesional', 'price' => 50000, 'setup_price' => 30000, 'max_users' => 20],
                ],
            ],
            [
                'name' => 'Sistema Contable',
                'slug' => 'sistema-contable',
                'short_description' => 'Contabilidad profesional y conciliaciones al día.',
                'description' => 'Registro de asientos, plan de cuentas, libros contables, conciliación bancaria y reportes para que el estudio contable trabaje sobre información confiable.',
                'price_from' => 55000,
                'installation_price' => 30000,
                'is_featured' => false,
                'is_published' => true,
                'features' => [
                    'Plan de cuentas configurable',
                    'Libros IVA y mayores',
                    'Conciliación bancaria',
                    'Cierre y reapertura de ejercicios',
                ],
                'plans' => [
                    ['name' => 'Estándar', 'slug' => 'estandar', 'price' => 55000, 'setup_price' => 30000, 'max_users' => 10],
                    ['name' => 'Plus', 'slug' => 'plus', 'price' => 90000, 'setup_price' => 40000, 'max_users' => null],
                ],
            ],
            [
                'name' => 'Liquidación de Sueldos',
                'slug' => 'liquidacion-de-sueldos',
                'short_description' => 'Recibos, cargas sociales y RRHH en orden.',
                'description' => 'Liquidación de sueldos y jornales, recibos digitales, altas y bajas, y reportes de cargas sociales. Diseñado para estudios y departamentos de RRHH.',
                'price_from' => 40000,
                'installation_price' => 28000,
                'is_featured' => true,
                'is_published' => true,
                'features' => [
                    'Liquidación mensual de sueldos',
                    'Recibos digitales',
                    'Altas, bajas y modificaciones',
                    'Reportes de cargas sociales',
                ],
                'plans' => [
                    ['name' => 'Básico', 'slug' => 'basico', 'price' => 40000, 'setup_price' => 28000, 'max_users' => 5],
                    ['name' => 'Completo', 'slug' => 'completo', 'price' => 65000, 'setup_price' => 35000, 'max_users' => 30],
                ],
            ],
        ];

        foreach ($systems as $systemData) {
            $plans = $systemData['plans'];
            $features = $systemData['features'];
            unset($systemData['plans'], $systemData['features']);

            $system = System::firstOrCreate(['slug' => $systemData['slug']], $systemData);

            foreach ($features as $index => $feature) {
                SystemFeature::firstOrCreate(
                    ['system_id' => $system->id, 'name' => $feature],
                    ['description' => null, 'sort_order' => $index],
                );
            }

            foreach ($plans as $index => $plan) {
                SystemPlan::firstOrCreate(
                    ['system_id' => $system->id, 'slug' => $plan['slug']],
                    [...$plan, 'description' => null, 'grace_days' => 10, 'billing_interval_days' => 30, 'is_active' => true, 'sort_order' => $index],
                );
            }
        }
    }
}
