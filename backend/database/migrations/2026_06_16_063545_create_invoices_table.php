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
        Schema::create('invoices', function (Blueprint $table) {

            $table->id();

            $table->foreignId('room_id')
                ->nullable()
                ->constrained('rooms')
                ->nullOnDelete();

            $table->foreignId('reading_id')
                ->nullable()
                ->constrained('utility_readings')
                ->nullOnDelete();

            $table->integer('month')->nullable();

            $table->integer('year')->nullable();

            $table->decimal('room_fee',12,2)->nullable();

            $table->decimal('electric_fee',12,2)->nullable();

            $table->decimal('water_fee',12,2)->nullable();

            $table->decimal('service_fee',12,2)->nullable();

            $table->decimal('total_amount',12,2)->nullable();

            $table->enum('status',[
                'ChuaThanhToan',
                'DaThanhToan',
                'QuaHan'
            ])->default('ChuaThanhToan');

            $table->unique([
                'room_id',
                'month',
                'year'
            ]);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
