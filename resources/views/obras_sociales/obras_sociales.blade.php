@extends('adminlte::page')

@section('title', 'Obras Sociales')

@section('content_header')
    <h1>Gestión de Obras Sociales</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalNuevaObraSocial">
                <i class="fas fa-plus"></i> Nueva Obra Social
            </button>
        </div>
        <div class="card-body">
            <table id="tabla-obras-sociales" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>CUIT</th>
                        <th>Fecha Convenio</th>
                        <th>Fecha Vencimiento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal Nueva Obra Social -->
    <div class="modal fade" id="modalNuevaObraSocial" tabindex="-1" role="dialog" aria-labelledby="modalNuevaObraSocialLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNuevaObraSocialLabel">Nueva Obra Social</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formNuevaObraSocial">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="cuit">CUIT</label>
                            <input type="text" class="form-control" id="cuit" name="cuit" required>
                        </div>
                        <div class="form-group">
                            <label for="fecha_convenio">Fecha Convenio</label>
                            <input type="date" class="form-control" id="fecha_convenio" name="fecha_convenio" required>
                        </div>
                        <div class="form-group">
                            <label for="fecha_vencimiento_convenio">Fecha Vencimiento</label>
                            <input type="date" class="form-control" id="fecha_vencimiento_convenio" name="fecha_vencimiento_convenio" required>
                        </div>
                        <div class="form-group">
                            <label for="codigo_validacion">Código de Validación</label>
                            <input type="text" class="form-control" id="codigo_validacion" name="codigo_validacion">
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

    <!-- Modal Editar Obra Social -->
    <div class="modal fade" id="modalEditarObraSocial" tabindex="-1" role="dialog" aria-labelledby="modalEditarObraSocialLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarObraSocialLabel">Editar Obra Social</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formEditarObraSocial">
                    @csrf
                    <input type="hidden" id="editar_id" name="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="editar_nombre">Nombre</label>
                            <input type="text" class="form-control" id="editar_nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="editar_cuit">CUIT</label>
                            <input type="text" class="form-control" id="editar_cuit" name="cuit" required>
                        </div>
                        <div class="form-group">
                            <label for="editar_fecha_convenio">Fecha Convenio</label>
                            <input type="date" class="form-control" id="editar_fecha_convenio" name="fecha_convenio" required>
                        </div>
                        <div class="form-group">
                            <label for="editar_fecha_vencimiento_convenio">Fecha Vencimiento</label>
                            <input type="date" class="form-control" id="editar_fecha_vencimiento_convenio" name="fecha_vencimiento_convenio" required>
                        </div>
                        <div class="form-group">
                            <label for="editar_codigo_validacion">Código de Validación</label>
                            <input type="text" class="form-control" id="editar_codigo_validacion" name="codigo_validacion">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Productos de Obra Social -->
    <div class="modal fade" id="modalProductosObraSocial" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Productos Cubiertos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Porcentaje Cobertura</th>
                            </tr>
                        </thead>
                        <tbody id="productos-body">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    {{-- SweetAlert2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

@stop

@section('js')
    {{-- jQuery y Bootstrap ya están cargados por AdminLTE --}}
    {{-- DataTables --}}
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Tu archivo JS --}}
    <script src="{{ asset('js/obras-sociales/index.js') }}"></script>
@stop