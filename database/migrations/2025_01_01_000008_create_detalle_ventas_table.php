<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_ventas', function (Blueprint $table) {
            $table->foreignId('id_venta')->constrained('ventas')->cascadeOnDelete();
            $table->foreignId('id_producto')->constrained('productos')->cascadeOnDelete();
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('precio_final', 10, 2)->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
            $table->primary(['id_venta', 'id_producto']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_ventas');
    }
};
