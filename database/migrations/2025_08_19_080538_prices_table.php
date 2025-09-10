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
        Schema::create("prices", function (Blueprint $table) {
            $table->id();
            $table->foreignId('variant_id')->constrained('id')->on('product_variants')->onDelete('cascade');
            $table->decimal('list_priced', 12, 2);
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
