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
            $table->string('code')->unique()->comment('Código único del producto');
            $table->string('name')->comment('Nombre del producto');
            $table->text('description')->nullable()->comment('Descripción del producto');

            // Campos específicos para vidriería
            $table->enum('unit_type', ['m2', 'm3', 'unidad'])->default('m2')->comment('Tipo de unidad (m2, m3, unidad)');
            $table->decimal('width', 8, 2)->nullable()->comment('Ancho en metros');
            $table->decimal('height', 8, 2)->nullable()->comment('Alto en metros');
            $table->decimal('thickness', 8, 3)->nullable()->comment('Espesor en milímetros');
            $table->decimal('area', 10, 2)->nullable()->comment('Área en metros cuadrados');
            $table->decimal('volume', 10, 3)->nullable()->comment('Volumen en metros cúbicos');


            // Campos de precio
            $table->decimal('price', 10, 2)->comment('Precio base del producto');
            $table->decimal('price_per_unit', 10, 2)->nullable()->comment('Precio por unidad de medida (m2, m3, unidad)');
            $table->smallInteger('discount')->default(0)->comment('Descuento aplicado al producto en porcentaje');

            // Tipo de vidrio y características
            $table->foreignId('glass_type_id')->nullable()->constrained()->nullOnDelete()->comment('Tipo de vidrio_id (templado, laminado, etc.)');
            $table->json('features')->nullable()->comment('Características especiales del vidrio, ej: color, resistencia, etc.');

            // Inventario
            $table->integer('stock')->default(0)->comment('Cantidad en stock (en la unidad correspondiente)');
            $table->integer('min_stock')->default(0)->comment('Stock mínimo recomendado');

            // Imagen y estado
            $table->string('image')->nullable()->comment('Ruta de la imagen del producto');
            $table->boolean('is_active')->default(true)->comment('Indica si el producto está activo');

            // Relaciones
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete()->comment('Categoría del producto');
            $table->foreignId('provider_id')->nullable()->constrained()->nullOnDelete()->comment('Proveedor del producto');

            $table->softDeletes()->comment('Marca el producto como eliminado sin borrarlo físicamente');
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
