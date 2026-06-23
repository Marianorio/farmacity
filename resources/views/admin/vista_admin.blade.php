@extends('adminlte::page')

@section('title', 'Gestión de Usuarios')

@section('content_header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <h1>Gestión de Usuarios</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <h3>Lista de Usuarios</h3>
                </div>
                <div class="col-6 text-right">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarUsuario">
                        <i class="fas fa-user-plus"></i> Nuevo Usuario
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped" id="tablaPrincipal">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->hasRole('Admin'))
                                <span class="badge badge-primary">Administrador</span>
                            @elseif($user->hasRole('Farmaceutico'))
                                <span class="badge badge-success">Farmacéutico</span>
                            @elseif($user->hasRole('Auxiliar'))
                                <span class="badge badge-warning">Auxiliar</span>
                            @elseif($user->hasRole('Cajero'))
                                <span class="badge badge-info">Cajero</span>
                            @else
                                <span class="badge badge-secondary">Sin rol</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-info btn-sm" onclick="editarUsuario({{ $user->id }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-secondary btn-sm" onclick="abrirModalPassword({{ $user->id }}, '{{ addslashes($user->email) }}')" title="Restablecer contraseña">
                                <i class="fas fa-key"></i>
                            </button>
                            <button class="btn btn-info btn-sm" onclick="generarPasswordTemporal({{ $user->id }})" title="Generar contraseña temporal">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-danger btn-sm eliminar-usuario" data-id="{{ $user->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para agregar usuario -->
    <div class="modal fade" id="modalAgregarUsuario" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('vista_admin.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="form-group">
                            <label>Contraseña</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="form-group">
                            <label>Rol</label>
                            <select class="form-control" name="role" required>
                                <option value="">Seleccionar Rol</option>
                                <option value="Admin">Administrador</option>
                                <option value="Farmaceutico">Farmacéutico</option>
                                <option value="Auxiliar">Auxiliar</option>
                                <option value="Cajero">Cajero</option>
                            </select>
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

    <!-- Modal para editar usuario -->
    <div class="modal fade" id="modalEditarUsuario" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="formEditar" method="POST">
                    @csrf
                    @method('POST')
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" class="form-control" name="name" id="edit_name" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" id="edit_email" required>
                        </div>
                        <div class="form-group">
                            <label>Rol</label>
                            <select class="form-control" name="role" id="edit_role" required>
                                <option value="Admin">Administrador</option>
                                <option value="Farmaceutico">Farmacéutico</option>
                                <option value="Auxiliar">Auxiliar</option>
                                <option value="Cajero">Cajero</option>
                            </select>
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

            <!-- Modal para restablecer contraseña -->
            <div class="modal fade" id="modalPassword" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Restablecer Contraseña</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <form id="formPassword">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" id="pwd_user_id" name="user_id">
                                <div class="form-group">
                                    <label>Nuevo Password</label>
                                    <input type="password" class="form-control" name="password" id="new_password" required minlength="8">
                                </div>
                                <div class="form-group">
                                    <label>Confirmar Password</label>
                                    <input type="password" class="form-control" name="password_confirmation" id="new_password_confirmation" required minlength="8">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Actualizar contraseña</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@stop

@section('css')
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    {{-- SweetAlert2 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@stop

@section('js')
    {{-- jQuery y Bootstrap JS ya están cargados por AdminLTE --}}
    
    {{-- DataTables JS --}}
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
    
    {{-- SweetAlert2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    {{-- Archivo de traducción --}}
    <script src="{{ asset('js/dataTables.spanish.js') }}"></script>
    
    <script>
        // Función editarUsuario definida globalmente
        function editarUsuario(id) {
            $.ajax({
                url: `/vista_admin/${id}/edit`,
                method: 'GET',
                success: function(response) {
                    $('#edit_name').val(response.user.name);
                    $('#edit_email').val(response.user.email);
                    $('#edit_role').val(response.role);
                    $('#formEditar').attr('action', `/vista_admin/${id}`);
                    $('#modalEditarUsuario').modal('show');
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al cargar los datos del usuario'
                    });
                }
            });
        }

        $(document).ready(function() {
            // Configuración de AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Inicialización de DataTables
            $('#tablaPrincipal').DataTable({
                "language": spanishTranslation,
                "pageLength": 10,
                "order": [[0, 'desc']],
                "responsive": true,
                "autoWidth": false
            });

            // Eliminar usuario
            $('.eliminar-usuario').click(function() {
                const id = $(this).data('id');
                
                Swal.fire({
                    title: '¿Confirmar eliminación?',
                    text: 'Esta acción no se puede deshacer',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/vista_admin/${id}`,
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if(response.success) {
                                    Swal.fire('¡Eliminado!', 'El usuario ha sido eliminado.', 'success')
                                        .then(() => location.reload());
                                } else {
                                    Swal.fire('Error', response.message || 'Error al eliminar el usuario', 'error');
                                }
                            },
                            error: function(xhr) {
                                let message = 'Error al eliminar el usuario';
                                if(xhr.status === 403) {
                                    message = 'No se pueden eliminar usuarios administrativos del sistema';
                                } else if(xhr.responseJSON && xhr.responseJSON.message) {
                                    message = xhr.responseJSON.message;
                                }
                                Swal.fire('Error', message, 'error');
                            }
                        });
                    }
                });
            });

            $('#formEditar').on('submit', function(e) {
                e.preventDefault();
                const action = $(this).attr('action');
                const formData = $(this).serialize();

                $.ajax({
                    url: action,
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if(response.success) {
                            $('#modalEditarUsuario').modal('hide');
                            // Mostrar mensaje de éxito
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: response.message
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Error al actualizar el usuario'
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('Error Response:', xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al actualizar el usuario: ' + 
                                  (xhr.responseJSON ? xhr.responseJSON.message : 'Error del servidor')
                        });
                    }
                });
            });

            $('#formCrearUsuario').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#modalAgregarUsuario').modal('hide');
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Error al crear el usuario');
                        console.error(xhr.responseText);
                    }
                });
            });

            // Abrir modal password
            window.abrirModalPassword = function(userId, userEmail) {
                $('#pwd_user_id').val(userId);
                $('#modalPassword').modal('show');
            }

            // Manejar formPassword
            $('#formPassword').on('submit', function(e) {
                e.preventDefault();
                var userId = $('#pwd_user_id').val();
                var url = `/vista_admin/${userId}/password`;
                var data = $(this).serialize();

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: data,
                    success: function(response) {
                        if(response.success) {
                            $('#modalPassword').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Contraseña actualizada',
                                text: response.message
                            });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error', text: response.message || 'No se pudo actualizar la contraseña' });
                        }
                    },
                    error: function(xhr) {
                        var msg = 'Error al actualizar la contraseña';
                        if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                        Swal.fire({ icon: 'error', title: 'Error', text: msg });
                    }
                });
            });

            // Generar contraseña temporal y mostrarla al admin (una sola vez)
            window.generarPasswordTemporal = function(userId) {
                var url = `/vista_admin/${userId}/generate-password`;
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {_token: $('meta[name="csrf-token"]').attr('content')},
                    success: function(response) {
                        if(response.success && response.password) {
                            Swal.fire({
                                title: 'Contraseña temporal',
                                html: `<p>Contraseña generada: <strong>${response.password}</strong></p><p>Comunícale esta contraseña al usuario. Pídale que la cambie al iniciar sesión.</p>`,
                                icon: 'info'
                            });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error', text: response.message || 'No se pudo generar contraseña' });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Error del servidor al generar contraseña' });
                    }
                });
            }
        });
    </script>
@stop