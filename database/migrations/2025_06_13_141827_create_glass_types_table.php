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
        Schema::create('glass_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Tipo de vidrio: templado, laminado, etc.');
            $table->text('description')->nullable()->comment('Descripción del tipo de vidrio');
            $table->softDeletes()->comment('Marca el tipo de vidrio como eliminado sin borrarlo físicamente');
            $table->boolean('is_active')->default(true)->comment('Indica si el tipo de vidrio está activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('glass_types');
    }
};
