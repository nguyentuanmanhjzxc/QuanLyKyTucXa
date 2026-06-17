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
        Schema::create('rooms', function (Blueprint $table) {

            $table->id();

            $table->foreignId('building_id')
                ->constrained('buildings')
                ->cascadeOnDelete();

            $table->string('room_code',20)
                ->unique();

            $table->integer('capacity');

            $table->integer('current_occupancy')
                ->default(0);

            $table->decimal('price',12,2);

            $table->enum('status',[
                'ConCho',
                'DayPhong',
                'BaoTri'
            ])->default('ConCho');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
