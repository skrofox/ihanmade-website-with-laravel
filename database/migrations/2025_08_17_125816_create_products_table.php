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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 150)->unique();
            $table->string('name', 200);
            $table->longText('description')->nullable();
            $table->string('brand', 100)->nullable();
            $table->enum('status', ['draft', 'active', 'inactive'])->default('active');
            $table->integer('dim_l_mm')->nullable();
            $table->integer('dim_w_mm')->nullable();
            $table->integer('dim_h_mm')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
