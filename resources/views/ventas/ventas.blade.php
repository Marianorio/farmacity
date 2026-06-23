@extends('adminlte::page')

@section('title', 'Ventas')

@section('content_header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <h1>Gestión de Ventas</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <h3>Lista de Ventas</h3>
                </div>
                <div class="col-6 text-right">
                    <button type="button" class="btn btn-success mr-2" id="btnReporteVentas">
                        <i class="fas fa-file-excel"></i> Reporte de Ventas
                    </button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#nuevaVentaModal">
                        <i class="fas fa-plus"></i> Nueva Venta
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped" id="tablaPrincipal">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>N° Cliente</th>
                        <th>Fecha</th>
                        <th>Cajero</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ventas as $venta)
                    <tr>
                        <td>{{ $venta->id }}</td>
                        <td>{{ $venta->numero_cliente }}</td>
                        <td>{{ $venta->fecha }}</td>
                        <td>{{ $venta->usuario ? $venta->usuario->name : 'Usuario no disponible' }}</td>
                        <td>${{ number_format($venta->total, 2) }}</td>
                        <td>
                            <span class="badge badge-{{ $venta->estado == 'COMPLETADA' ? 'success' : ($venta->estado == 'ANULADA' ? 'danger' : 'warning') }}">
                                {{ $venta->estado }}
                                @if($venta->estado == 'ANULADA' && $venta->usuarioAnulacion)
                                    <br><small>Por: {{ $venta->usuarioAnulacion->name }}</small>
                                @endif
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-info btn-sm ver-venta" data-id="{{ $venta->id }}">
                                <i class="fas fa-eye"></i>
                            </button>
                            @if($venta->estado !== 'ANULADA')
                                <button class="btn btn-warning btn-sm anular-venta" data-id="{{ $venta->id }}">
                                    <i class="fas fa-ban"></i>
                                </button>
                            @endif
                            <button class="btn btn-primary btn-sm" onclick="window.open('/ventas/{{ $venta->id }}/pdf', '_blank')">
                                <i class="fas fa-print"></i>
                            </button>
                            <!-- Boton eliminar comentado por si se necesita en el futuro

                            <button class="btn btn-danger btn-sm eliminar-venta" data-id="{{ $venta->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                            
                            -->
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Nueva Venta -->
    <div class="modal fade" id="nuevaVentaModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Venta</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formNuevaVenta">
                        <div class="form-group row">
                            <div class="col-4">
                                <label>Número de Venta</label>
                                <input type="text" class="form-control" id="numeroVenta" readonly>
                            </div>
                            <div class="col-4">
                                <label>Número de Cliente</label>
                                <input type="text" class="form-control" id="numeroCliente" readonly>
                            </div>
                            <div class="col-4">
                                <label>Fecha</label>
                                <input type="text" class="form-control" id="fechaVenta" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Obra Social</label>
                            <select class="form-control" id="obraSocial">
                                <option value="">Seleccione una obra social</option>
                                @foreach($obrasSociales as $obraSocial)
                                    <option value="{{ $obraSocial->id }}">{{ $obraSocial->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" id="codigoValidacionContainer" style="display: none;">
                            <label>Código de Validación</label>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control" 
                                       id="codigoValidacion" 
                                       placeholder="Ingrese código de validación">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="codigoValidacionStatus"></span>
                                </div>
</div>
                        </div>
                        <div class="form-group">
                            <label for="buscadorProductos">Buscar Producto</label>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control" 
                                       id="buscadorProductos" 
                                       placeholder="Buscar productos...">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>
                            </div>
                            <ul class="list-group" id="listaProductos" style="display: none;">
                                <!-- Los productos se agregarán aquí dinámicamente -->
                            </ul>
                        </div>
                        <div id="ventaErrors" class="alert alert-danger" style="display:none;"></div>
                        <div class="productos-container">
                            <h4>Productos Seleccionados</h4>
                            <ul id="listaCarrito" class="list-group"></ul> <!-- Aquí se mostrarán los productos agregados -->
                        </div>
                        <div class="form-group">
                            <h4>Resumen de la Venta</h4>
                            <div class="row">
                                <div class="col-6">
                                    <label>Subtotal</label>
                                    <input type="text" class="form-control" id="subtotal" value="0" readonly>
                                </div>
                                <div class="col-6">
                                    <label>Impuestos</label>
                                    <input type="text" class="form-control" id="impuestos" value="0" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label>Descuentos</label>
                                    <input type="number" class="form-control" id="descuentos" value="0" oninput="actualizarTotal()">
                                </div>
                                <div class="col-6">
                                    <label>Total</label>
                                    <input type="text" class="form-control" id="totalAPagar" value="0" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Método de Pago</label>
                            <select class="form-control" id="metodoPago">
                                <option value="efectivo">Efectivo</option>
                                <option value="tarjeta">Tarjeta</option>
                                <option value="combinado">Combinado</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar Venta</button>
                    <button type="button" class="btn btn-primary" id="guardarVenta">Confirmar Venta</button>
                    <button type="button" class="btn btn-danger" id="procesarDevolucion">Procesar Devolución</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Anulación -->
    <div class="modal fade" id="anularVentaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Anular Venta</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formAnularVenta">
                        <input type="hidden" id="ventaIdAnular">
                        <div class="form-group">
                            <label>Motivo de Anulación</label>
                            <textarea class="form-control" id="motivoAnulacion" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmarAnulacion">Confirmar Anulación</button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        #listaProductos {
            max-height: 300px;
            overflow-y: auto;
            position: absolute;
            width: 100%;
            z-index: 1000;
        }
        #codigoValidacionStatus {
            min-width: 40px;
            text-align: center;
        }
        .is-valid {
            border-color: #28a745 !important;
        }
        .is-invalid {
            border-color: #dc3545 !important;
        }
    </style>
@stop

@section('js')
    {{-- jQuery y Bootstrap ya están cargados por AdminLTE --}}
    
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    
    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    
    <!-- Tu archivo JavaScript personalizado -->
    <script src="{{ asset('js/ventas.js') }}"></script>
    
    <script>
        // Asegurarse de que jQuery está cargado
        if (typeof jQuery === 'undefined') {
            console.error('jQuery no está cargado!');
        }

        // Inicializar DataTables solo si no está ya inicializada
        $(document).ready(function() {
            if (!$.fn.DataTable.isDataTable('#tablaPrincipal')) {
                $('#tablaPrincipal').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
                    },
                    "order": [[0, "desc"]]
                });
            }
        });

        // Definir la variable productos fuera del document.ready
        const productos = {!! json_encode($productos) !!};

        function toggleCodigoValidacion() {
            const obraSocial = document.getElementById('obraSocial').value;
            const codigoValidacionContainer = document.getElementById('codigoValidacionContainer');
            if (obraSocial) {
                codigoValidacionContainer.style.display = 'block';
            } else {
                codigoValidacionContainer.style.display = 'none';
            }
        }
    </script>
@stop
