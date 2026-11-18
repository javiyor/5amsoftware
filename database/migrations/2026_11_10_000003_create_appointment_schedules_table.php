<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_schedules', function (Blueprint $table) {
            $table->id();
            $table->json('open_days');
            $table->time('open_time');
            $table->time('close_time');
            $table->unsignedSmallInteger('duration_minutes')->default(30);
            $table->timestamps();
        });

        DB::table('appointment_schedules')->insert([
            'id' => 1,
            'open_days' => json_encode([1, 2, 3, 4, 5]),
            'open_time' => '09:00:00',
            'close_time' => '17:00:00',
            'duration_minutes' => 30,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_schedules');
    }
};
