<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_visit_counters', function (Blueprint $table) {
            $table->string('key', 120)->primary();
            $table->unsignedBigInteger('visits')->default(0);
            $table->timestamps();
        });

        foreach (['total', 'home', 'systems'] as $key) {
            DB::table('site_visit_counters')->insert([
                'key' => $key,
                'visits' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('site_visit_counters');
    }
};
