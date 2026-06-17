<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('room_assignments', function (Blueprint $table) {
                $table->id();

                $table->foreignId('student_id')
                    ->nullable()
                    ->constrained('students')
                    ->nullOnDelete();

                $table->foreignId('room_id')
                    ->nullable()
                    ->constrained('rooms')
                    ->nullOnDelete();

                $table->date('check_in_date')->nullable();

                $table->date('check_out_date')->nullable();

                $table->enum('status', [
                    'DangO',
                    'DaTraPhong'
                ])->default('DangO');

                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_assignments');
    }
};
