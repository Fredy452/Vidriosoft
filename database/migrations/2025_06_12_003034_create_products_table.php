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
            $table->string('name')->comment('Nombre del producto');
            // product_categories referemces
            $table->foreignId('category_id')
                ->constrained('product_categories')
                ->onDelete('cascade')
                ->comment('ID de la categoría del producto');

            // product_providers references
            $table->foreignId('provider_id')
                ->constrained('providers')
                ->onDelete('cascade')
                ->comment('ID del proveedor del producto');
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
