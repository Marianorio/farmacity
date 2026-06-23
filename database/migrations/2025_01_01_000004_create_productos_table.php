<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->decimal('precio_compra', 10, 2);
            $table->decimal('precio_venta', 10, 2);
            $table->integer('stock_inicial');
            $table->integer('stock_actual');
            $table->integer('stock_minimo');
            $table->date('caducidad')->nullable();
            $table->foreignId('id_categoria')->nullable()->constrained('categorias')->nullOnDelete();
            $table->foreignId('id_proveedor')->nullable()->constrained('proveedores')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
