<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->string('numero_cliente', 10)->nullable();
            $table->timestamp('fecha')->useCurrent();
            $table->decimal('subtotal', 10, 2)->nullable();
            $table->decimal('impuestos', 10, 2)->nullable();
            $table->decimal('descuento', 10, 2)->nullable();
            $table->decimal('total', 10, 2);
            $table->foreignId('id_usuario')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('id_obra_social')->nullable()->constrained('obras_sociales')->nullOnDelete();
            $table->string('codigo_validacion', 50)->nullable();
            $table->string('estado', 255)->default('ACTIVA');
            $table->foreignId('id_usuario_anulacion')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('fecha_anulacion')->nullable();
            $table->text('motivo_anulacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
