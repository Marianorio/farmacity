<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos_obras_sociales', function (Blueprint $table) {
            $table->foreignId('id_producto')->constrained('productos')->cascadeOnDelete();
            $table->foreignId('id_obra_social')->constrained('obras_sociales')->cascadeOnDelete();
            $table->decimal('descuento', 5, 2)->nullable();
            $table->primary(['id_producto', 'id_obra_social']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos_obras_sociales');
    }
};
