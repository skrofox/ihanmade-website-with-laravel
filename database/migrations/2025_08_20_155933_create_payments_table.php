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
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('provider', 50); // e.g. 'mock', 'cod'
            $table->string('transaction_id', 100)->nullable();
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['pending','authorized','captured','failed','refunded'])->default('pending');
            $table->enum('method', ['cod','bank_transfer','card','e_wallet'])->default('cod');
            $table->dateTime('paid_at')->nullable();
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
