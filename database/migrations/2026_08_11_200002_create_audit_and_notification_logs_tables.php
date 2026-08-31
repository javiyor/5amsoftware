<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->string('entity_type')->nullable()->index();
            $table->unsignedBigInteger('entity_id')->nullable()->index();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['entity_type', 'entity_id', 'action']);
        });

        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->string('notifiable_type');
            $table->unsignedBigInteger('notifiable_id');
            $table->string('notification_type');
            $table->string('channel')->default('email');
            $table->timestamp('sent_at')->useCurrent();
            $table->json('data')->nullable();
            $table->timestamps();

            $table->unique(
                ['notifiable_type', 'notifiable_id', 'notification_type', 'channel', 'sent_at'],
                'notification_logs_dedup',
            );
            $table->index(['notifiable_type', 'notifiable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
        Schema::dropIfExists('audit_logs');
    }
};
