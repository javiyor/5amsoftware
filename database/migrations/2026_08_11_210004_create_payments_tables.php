<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('payment_number')->unique();
            $table->string('payment_method');
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('ARS');
            $table->string('status')->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->string('provider')->nullable();
            $table->string('provider_payment_id')->nullable();
            $table->string('provider_preference_id')->nullable();
            $table->string('provider_external_reference')->nullable();
            $table->json('raw_response')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['provider', 'provider_payment_id']);
            $table->index('customer_id');
            $table->index('status');
        });

        Schema::create('payment_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payments')->cascadeOnDelete();
            $table->foreignId('installment_id')->constrained('installments')->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->timestamps();

            $table->unique(['payment_id', 'installment_id']);
        });

        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->string('provider');
            $table->string('event_type');
            $table->string('provider_event_id')->nullable();
            $table->string('provider_payment_id')->nullable();
            $table->string('status')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->text('processing_error')->nullable();
            $table->timestamps();

            $table->unique(['provider', 'event_type', 'provider_event_id'], 'payments_tx_uniq_provider_event');
            $table->index('payment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
        Schema::dropIfExists('payment_installments');
        Schema::dropIfExists('payments');
    }
};
