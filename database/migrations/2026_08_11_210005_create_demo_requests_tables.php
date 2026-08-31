<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demo_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id')->nullable()->constrained('systems')->nullOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('company_name')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('city')->nullable();
            $table->unsignedInteger('number_of_users')->nullable();
            $table->date('preferred_date')->nullable();
            $table->time('preferred_time')->nullable();
            $table->text('comments')->nullable();
            $table->string('status')->default('new');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('converted_customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->timestamps();

            $table->index('status');
            $table->index('email');
        });

        Schema::create('demo_request_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demo_request_id')->constrained('demo_requests')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->text('comment')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demo_request_logs');
        Schema::dropIfExists('demo_requests');
    }
};
