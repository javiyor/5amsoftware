<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_systems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('system_id')->constrained('systems')->restrictOnDelete();
            $table->foreignId('system_plan_id')->nullable()->constrained('system_plans')->nullOnDelete();

            $table->string('contract_number')->unique();
            $table->string('custom_name')->nullable();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('billing_started_at')->nullable();
            $table->timestamp('next_billing_at')->nullable();

            $table->unsignedInteger('billing_interval_days')->default(30);
            $table->unsignedInteger('grace_days')->default(10);

            $table->decimal('monthly_amount', 15, 2);
            $table->decimal('setup_amount', 15, 2)->default(0);
            $table->string('currency', 3)->default('ARS');

            $table->boolean('automatic_billing')->default(true);
            $table->boolean('automatic_suspension')->default(true);

            $table->string('status')->default('pending');
            $table->boolean('manual_suspension')->default(false);
            $table->string('manual_suspension_reason')->nullable();
            $table->timestamp('grace_override_until')->nullable();

            $table->string('domain')->nullable();
            $table->string('installation_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('customer_id');
            $table->index('system_id');
            $table->index('status');
            $table->index('next_billing_at');
        });

        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_system_id')->unique()->constrained('customer_systems')->cascadeOnDelete();
            $table->string('license_key')->unique();
            $table->string('api_token_hash')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('last_check_at')->nullable();
            $table->string('last_ip', 45)->nullable();
            $table->string('domain')->nullable();
            $table->string('installation_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('licenses');
        Schema::dropIfExists('customer_systems');
    }
};
