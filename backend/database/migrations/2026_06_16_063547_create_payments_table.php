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
        Schema::create('payments', function (Blueprint $table) {

            $table->id();

            $table->foreignId('invoice_id')
                ->nullable()
                ->constrained('invoices')
                ->nullOnDelete();

            $table->enum('payment_method',[
                'Cash',
                'Banking',
            ])->nullable();

            $table->decimal('amount',12,2)->nullable();

            $table->dateTime('paid_at')->nullable();

            $table->enum('status',[
                'Success',
                'Failed'
            ])->nullable();

            $table->string('transaction_code',100)
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
