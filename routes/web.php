<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    UserController,
    VistaAdminController,
    HomeController,
    ProveedorController,
    ProductoController,
    VentasController,
    ReportesController,
    ObraSocialController,
    CategoriaController
};

/*-------------------------- INICIO --------------------------*/
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

/*-------------------------- VISTAS BÁSICAS --------------------------*/
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])
        ->name('home')
        ->middleware('can:home');
        
    Route::get('/perfil', [UserController::class, 'index'])
        ->name('user.profile')
        ->middleware('can:perfil');
    
    Route::post('/perfil/actualizar', [UserController::class, 'update'])
        ->name('user.update');

    Route::get('/info', function () { 
        return view('info.info'); 
    })->name('info')->middleware('can:info');
    
    Route::get('/recetas', function () { 
        return view('recetas.recetas'); 
    })->name('recetas')->middleware('can:recetas');
});

/*-------------------------- USUARIOS Y ADMIN --------------------------*/
Route::middleware(['auth'])->group(function () {
    Route::get('/vista_admin', [VistaAdminController::class, 'index'])
        ->name('vista_admin')
        ->middleware('can:vista_admin');

    Route::post('/vista_admin', [VistaAdminController::class, 'store'])
        ->name('vista_admin.store')
        ->middleware('can:vista_admin');

    Route::get('/vista_admin/{id}/edit', [VistaAdminController::class, 'edit'])
        ->name('vista_admin.edit')
        ->middleware('can:vista_admin');

    Route::post('/vista_admin/{id}', [VistaAdminController::class, 'update'])
        ->name('vista_admin.update')
        ->middleware('can:vista_admin');

    Route::delete('/vista_admin/{id}', [VistaAdminController::class, 'destroy'])
        ->name('vista_admin.destroy')
        ->middleware('can:vista_admin');

    // Actualizar contraseña de usuario (admin)
    Route::post('/vista_admin/{id}/password', [VistaAdminController::class, 'updatePassword'])
        ->name('vista_admin.update_password')
        ->middleware('can:vista_admin');

    // Generar y mostrar una contraseña temporal (se guarda hasheada)
    Route::post('/vista_admin/{id}/generate-password', [VistaAdminController::class, 'generateTemporaryPassword'])
        ->name('vista_admin.generate_password')
        ->middleware('can:vista_admin');
});

/*-------------------------- PRODUCTOS --------------------------*/
Route::middleware(['auth'])->group(function () {
    Route::prefix('productos')->group(function () {
        Route::get('/', [ProductoController::class, 'index'])
            ->name('productos.index')
            ->middleware('can:productos');

        Route::get('/bajo-stock', [ProductoController::class, 'productsLowStock'])
            ->name('productos.bajo_stock')
            ->middleware('can:productos');

        Route::get('/por-vencer', [ProductoController::class, 'productsExpiringSoon'])
            ->name('productos.por_vencer')
            ->middleware('can:productos');

        Route::get('/buscar', [ProductoController::class, 'buscarProductos'])
            ->name('productos.buscar')
            ->middleware('can:productos');

        Route::post('/', [ProductoController::class, 'store'])
            ->name('productos.store')
            ->middleware('can:productos');

        Route::get('/{id}', [ProductoController::class, 'show'])
            ->name('productos.show')
            ->middleware('can:productos');

        Route::put('/{id}', [ProductoController::class, 'update'])
            ->name('productos.update')
            ->middleware('can:productos');

        Route::delete('/{id}', [ProductoController::class, 'destroy'])
            ->name('productos.destroy')
            ->middleware('can:productos');
    });
});

/*-------------------------- OBRAS SOCIALES --------------------------*/
Route::middleware(['auth'])->group(function () {
    Route::prefix('obras-sociales')->group(function () {
        Route::get('/', [ObraSocialController::class, 'index'])
            ->name('obras-sociales.index')
            ->middleware('can:obras_sociales');

        Route::get('/lista', [ObraSocialController::class, 'lista'])
            ->name('obras-sociales.lista')
            ->middleware('can:obras_sociales');

        Route::get('/data', [ObraSocialController::class, 'getData'])
            ->name('obras-sociales.data')
            ->middleware('can:obras_sociales');

        Route::get('/{id}/productos', [ObraSocialController::class, 'productos'])
            ->name('obras-sociales.productos')
            ->middleware('can:obras_sociales');

        Route::post('/', [ObraSocialController::class, 'store'])
            ->name('obras-sociales.store')
            ->middleware('can:obras_sociales');

        Route::get('/{id}', [ObraSocialController::class, 'show'])
            ->name('obras-sociales.show')
            ->middleware('can:obras_sociales');

        Route::put('/{id}', [ObraSocialController::class, 'update'])
            ->name('obras-sociales.update')
            ->middleware('can:obras_sociales');

        Route::delete('/{id}', [ObraSocialController::class, 'destroy'])
            ->name('obras-sociales.destroy')
            ->middleware('can:obras_sociales');
    });
});

/*-------------------------- PROVEEDORES --------------------------*/
Route::middleware(['auth'])->group(function () {
    Route::prefix('proveedores')->group(function () {
        Route::get('/', [ProveedorController::class, 'index'])
            ->name('proveedores.index')
            ->middleware('can:proveedores');

        Route::get('/lista', [ProveedorController::class, 'getLista'])
            ->name('proveedores.lista')
            ->middleware('can:proveedores');

        Route::get('/{id}/productos', [ProveedorController::class, 'getProductos'])
            ->name('proveedores.productos')
            ->middleware('can:proveedores');

        Route::get('/{id}/create-pedido', [ProveedorController::class, 'createPedido'])
            ->name('proveedores.create-pedido')
            ->middleware('can:proveedores');

        Route::post('/', [ProveedorController::class, 'store'])
            ->name('proveedores.store')
            ->middleware('can:proveedores');

        Route::get('/{id}', [ProveedorController::class, 'show'])
            ->name('proveedores.show')
            ->middleware('can:proveedores');

        Route::put('/{id}', [ProveedorController::class, 'update'])
            ->name('proveedores.update')
            ->middleware('can:proveedores');

        Route::delete('/{id}', [ProveedorController::class, 'destroy'])
            ->name('proveedores.destroy')
            ->middleware('can:proveedores');
    });
});

/*-------------------------- VENTAS --------------------------*/
Route::middleware(['auth'])->group(function () {
    Route::prefix('ventas')->group(function () {
        Route::get('/reporte', [VentasController::class, 'generarReporte'])
            ->name('ventas.reporte')
            ->middleware('can:ventas');

        Route::get('/chart/data', [VentasController::class, 'getChartData'])
            ->name('ventas.chart_data')
            ->middleware('can:ventas');

        Route::get('/', [VentasController::class, 'index'])
            ->name('ventas.index')
            ->middleware('can:ventas');

        Route::post('/', [VentasController::class, 'store'])
            ->name('ventas.store')
            ->middleware('can:ventas');

        // AJAX endpoints used by the ventas UI (buscar productos, cobertura, validaciones, números)
        Route::post('/buscar-productos', [VentasController::class, 'buscarProductos'])
            ->name('ventas.buscar_productos')
            ->middleware('can:ventas');

        Route::post('/verificar-cobertura', [VentasController::class, 'verificarCobertura'])
            ->name('ventas.verificar_cobertura')
            ->middleware('can:ventas');

        Route::post('/validar-codigo', [VentasController::class, 'validarCodigo'])
            ->name('ventas.validar_codigo')
            ->middleware('can:ventas');

        Route::get('/proximo-numero-cliente', [VentasController::class, 'proximoNumeroCliente'])
            ->name('ventas.proximo_numero_cliente')
            ->middleware('can:ventas');

        Route::get('/proximo-numero-venta', [VentasController::class, 'proximoNumeroVenta'])
            ->name('ventas.proximo_numero_venta')
            ->middleware('can:ventas');

        Route::post('/{id}/anular', [VentasController::class, 'anular'])
            ->name('ventas.anular')
            ->middleware('can:ventas');

        Route::get('/{id}/pdf', [VentasController::class, 'generarPDF'])
            ->name('ventas.pdf')
            ->middleware('can:ventas');

        Route::get('/{id}', [VentasController::class, 'show'])
            ->name('ventas.show')
            ->middleware('can:ventas');

        Route::delete('/{id}', [VentasController::class, 'destroy'])
            ->name('ventas.destroy')
            ->middleware('can:ventas');
    });
});

/*-------------------------- REPORTES --------------------------*/
Route::middleware(['auth'])->group(function () {
    Route::prefix('reportes')->group(function () {
        Route::get('/', [ReportesController::class, 'index'])
            ->name('reportes.index')
            ->middleware('can:reportes');

        Route::post('/generar', [ReportesController::class, 'generarReporte'])
            ->name('reportes.generar')
            ->middleware('can:reportes');
    });
});

/*-------------------------- CATEGORIAS --------------------------*/
Route::middleware(['auth'])->group(function () {
    Route::prefix('categorias')->group(function () {
        Route::get('/', [CategoriaController::class, 'index'])
            ->name('categorias.index')
            ->middleware('can:categorias');

        Route::get('/lista', [CategoriaController::class, 'lista'])
            ->name('categorias.lista');

        Route::post('/', [CategoriaController::class, 'store'])
            ->name('categorias.store')
            ->middleware('can:categorias');

        Route::get('/{id}', [CategoriaController::class, 'show'])
            ->name('categorias.show')
            ->middleware('can:categorias');

        Route::put('/{id}', [CategoriaController::class, 'update'])
            ->name('categorias.update')
            ->middleware('can:categorias');

        Route::delete('/{id}', [CategoriaController::class, 'destroy'])
            ->name('categorias.destroy')
            ->middleware('can:categorias');
    });
});


