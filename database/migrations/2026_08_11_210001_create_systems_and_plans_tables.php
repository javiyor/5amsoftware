<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('systems', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('short_description');
            $table->longText('description');
            $table->string('main_image')->nullable();
            $table->decimal('price_from', 15, 2)->nullable();
            $table->decimal('installation_price', 15, 2)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('seo_title')->nullable();
            $table->string('seo_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('system_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id')->constrained('systems')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('system_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id')->constrained('systems')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->decimal('setup_price', 15, 2)->nullable();
            $table->unsignedInteger('billing_interval_days')->default(30);
            $table->unsignedInteger('grace_days')->default(10);
            $table->unsignedInteger('max_users')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['system_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_plans');
        Schema::dropIfExists('system_features');
        Schema::dropIfExists('systems');
    }
};
