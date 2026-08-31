<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_system_id')->constrained('customer_systems')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->unsignedInteger('number');
            $table->date('period_start');
            $table->date('period_end');
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->decimal('subtotal', 15, 2);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('interest_amount', 15, 2)->default(0);
            $table->decimal('total', 15, 2);
            $table->string('status')->default('pending');
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['customer_system_id', 'period_start', 'period_end']);
            $table->index('customer_id');
            $table->index('status');
            $table->index('due_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('installments');
    }
};
