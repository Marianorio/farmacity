@extends('adminlte::page')

@section('title', 'Proveedores')

@section('content_header')
    <h1>Gestión de Proveedores</h1>
@stop

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="card">
        <div class="card-header">
            <button class="btn btn-primary" onclick="nuevoProveedor()">
                <i class="fas fa-plus"></i> Nuevo Proveedor
            </button>
        </div>
        <div class="card-body">
            <table id="tabla-proveedores" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Contacto</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal Proveedor -->
    <div class="modal fade" id="modalProveedor" tabindex="-1" role="dialog" aria-labelledby="modalProveedorLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalProveedorLabel">Nuevo Proveedor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formProveedor">
                    @csrf
                    <input type="hidden" id="id" name="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="contacto">Contacto</label>
                            <input type="text" class="form-control" id="contacto" name="contacto" required>
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" required>
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para mostrar productos -->
    <div class="modal fade" id="modalProductosProveedor" tabindex="-1" role="dialog" aria-labelledby="modalProductosProveedorLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalProductosProveedorLabel">Productos del Proveedor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="productos-por-categoria">
                    <!-- Aquí se cargarán los productos -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para realizar pedido -->
    <div class="modal fade" id="modalRealizarPedido" tabindex="-1" role="dialog" aria-labelledby="modalRealizarPedidoLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRealizarPedidoLabel">Realizar Pedido</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formRealizarPedido">
                    @csrf
                    <input type="hidden" id="proveedor_id" name="proveedor_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nombre_empresa">Nombre de la Empresa</label>
                            <input type="text" class="form-control" id="nombre_empresa" name="nombre_empresa" readonly>
                        </div>
                        <div class="form-group">
                            <label for="nombre_contacto">Nombre del Contacto</label>
                            <input type="text" class="form-control" id="nombre_contacto" name="nombre_contacto" readonly>
                        </div>
                        <div class="form-group">
                            <label for="fecha_pedido">Fecha del Pedido</label>
                            <input type="date" class="form-control" id="fecha_pedido" name="fecha_pedido" required>
                        </div>
                        <div class="form-group">
                            <label for="nombre_producto">Nombre del Producto</label>
                            <input type="text" class="form-control" id="nombre_producto" name="nombre_producto" required>
                        </div>
                        <div class="form-group">
                            <label for="cantidad">Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success">Realizar Pedido</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@stop

@section('js')
    {{-- jQuery y Bootstrap ya están cargados por AdminLTE --}}
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/proveedores.js') }}"></script>
@stop