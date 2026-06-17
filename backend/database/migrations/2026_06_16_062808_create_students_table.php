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
        Schema::create('students', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('student_code',20)
                ->unique();

            $table->string('full_name',100);

            $table->enum('gender',[
                'Nam',
                'Nu'
            ])->nullable();

            $table->date('dob')->nullable();

            $table->string('phone',20)->nullable();

            $table->string('email',100)->nullable();

            $table->string('faculty',100)->nullable();

            $table->string('class_name',50)->nullable();

            $table->string('guardian_name',100)->nullable();

            $table->string('guardian_phone',20)->nullable();

            $table->enum('status',[
                'ChoDuyet',
                'DangO',
                'DaTraPhong',
                'BuocThoiO'
            ])->default('ChoDuyet');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
