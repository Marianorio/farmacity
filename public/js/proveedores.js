document.addEventListener('DOMContentLoaded', function() {
    // Inicializar DataTable
    const tablaProveedores = $('#tabla-proveedores').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/proveedores',
        columns: [
            { data: 'id' },
            { data: 'nombre' },
            { data: 'contacto' },
            { data: 'direccion' },
            { data: 'telefono' },
            { data: 'email' },
            { 
                data: null,
                orderable: false,
                render: function(data, type, row) {
                    return `
                        <div class="btn-group">
                            <button class="btn btn-success btn-sm ver-proveedor" data-id="${row.id}">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-info btn-sm ver-productos" data-id="${row.id}">
                                <i class="fas fa-box"></i>
                            </button>
                            <button class="btn btn-primary btn-sm editar-proveedor" data-id="${row.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm eliminar-proveedor" data-id="${row.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
        }
    });

    // Definición de la función nuevoProveedor
    window.nuevoProveedor = function() {
        $('#formProveedor')[0].reset(); // Limpiar el formulario
        $('#id').val(''); // Limpiar el ID para nuevo proveedor
        $('#modalProveedorLabel').text('Nuevo Proveedor'); // Cambiar el título del modal
        $('#modalProveedor').modal('show'); // Mostrar el modal
    };

    // Modal para nuevo proveedor
    const btnNuevoProveedor = document.querySelector('.btn-primary');
    const proveedorForm = document.getElementById('formProveedor');
    
    if (btnNuevoProveedor) {
        btnNuevoProveedor.addEventListener('click', function() {
            $('#modalProveedor').modal('show');
            if (proveedorForm) {
                proveedorForm.reset();
                $('#id').val(''); // Limpiar el ID para nuevo proveedor
                $('#modalProveedorLabel').text('Nuevo Proveedor');
            }
        });
    }

    // Manejar el envío del formulario
    if (proveedorForm) {
        proveedorForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = $('#id').val();
            const url = id ? `/proveedores/${id}` : '/proveedores';
            
            if (id) {
                formData.append('_method', 'PUT');
            }

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    title: '¡Éxito!',
                    text: data.success,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    $('#modalProveedor').modal('hide');
                    tablaProveedores.ajax.reload(); // Recargar la tabla
                });
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al guardar el proveedor',
                    icon: 'error'
                });
            });
        });
    }

    // Ver detalles del proveedor
    $(document).on('click', '.ver-proveedor', function() {
        const id = $(this).data('id');

        fetch(`/proveedores/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const proveedor = data.data;
                    
                    Swal.fire({
                        title: '<i class="fas fa-building"></i> Detalles del Proveedor',
                        html: `
                            <div class="text-start">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">${proveedor.nombre}</h5>
                                    </div>
                                </div>
                                
                                <table class="table table-sm table-borderless">
                                    <tbody>
                                        <tr>
                                            <td><strong>ID:</strong></td>
                                            <td><span class="badge bg-secondary">${proveedor.id}</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Contacto:</strong></td>
                                            <td>${proveedor.contacto}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Dirección:</strong></td>
                                            <td>${proveedor.direccion}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Teléfono:</strong></td>
                                            <td><a href="tel:${proveedor.telefono}">${proveedor.telefono}</a></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td><a href="mailto:${proveedor.email}">${proveedor.email}</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        `,
                        width: '600px',
                        confirmButtonText: 'Cerrar',
                        confirmButtonColor: '#3085d6',
                        didOpen: function() {
                            document.querySelector('.swal2-html-container').style.textAlign = 'left';
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'No se pudo obtener los detalles del proveedor', 'error');
            });
    });

    // Ver productos asociados al proveedor
    $(document).on('click', '.ver-productos', function() {
        const id = $(this).data('id');

        fetch(`/proveedores/${id}/productos`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let productosHTML = '<p class="text-muted">No hay productos relacionados</p>';
                    
                    if (data.productos && data.productos.length > 0) {
                        productosHTML = `
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th>Precio Compra</th>
                                        <th>Stock Actual</th>
                                        <th>Caducidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${data.productos.map(p => `
                                        <tr>
                                            <td><strong>${p.nombre}</strong></td>
                                            <td><span class="badge bg-warning text-dark">$${parseFloat(p.precio_compra).toFixed(2)}</span></td>
                                            <td>${p.stock_actual}</td>
                                            <td>${p.caducidad ? new Date(p.caducidad).toLocaleDateString('es-ES') : 'N/A'}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        `;
                    }
                    
                    Swal.fire({
                        title: '<i class="fas fa-box"></i> Productos del Proveedor',
                        html: productosHTML,
                        width: '700px',
                        confirmButtonText: 'Cerrar',
                        confirmButtonColor: '#3085d6',
                        didOpen: function() {
                            document.querySelector('.swal2-html-container').style.textAlign = 'left';
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'No se pudieron cargar los productos', 'error');
            });
    });

    // Manejar la edición de proveedores
    $(document).on('click', '.editar-proveedor', function() {
        const id = $(this).data('id');

        $.get(`/proveedores/${id}`, function(response) {
            const proveedor = response.data;
            $('#id').val(proveedor.id);
            $('#nombre').val(proveedor.nombre);
            $('#contacto').val(proveedor.contacto);
            $('#direccion').val(proveedor.direccion);
            $('#telefono').val(proveedor.telefono);
            $('#email').val(proveedor.email);
            $('#modalProveedorLabel').text('Editar Proveedor');
            $('#modalProveedor').modal('show');
        }).fail(function(xhr) {
            console.error('Error al cargar el proveedor:', xhr);
            Swal.fire('Error', 'No se pudo cargar el proveedor', 'error');
        });
    });

    // Manejar la eliminación de proveedores
    $(document).on('click', '.eliminar-proveedor', function() {
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
                fetch(`/proveedores/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        '_method': 'DELETE'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    tablaProveedores.ajax.reload();
                    Swal.fire('¡Eliminado!', 'El proveedor ha sido eliminado.', 'success');
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'No se pudo eliminar el proveedor.', 'error');
                });
            }
        });
    });

    // Función para realizar compra
    $(document).on('click', '.realizar-compra', function() {
        const id = $(this).data('id');

        fetch(`/proveedores/${id}/create-pedido`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#proveedor_id').val(data.proveedor.id);
                    $('#nombre_empresa').val(data.proveedor.nombre);
                    $('#nombre_contacto').val(data.proveedor.contacto);
                    $('#modalRealizarPedido').modal('show');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'No se pudo cargar la información del proveedor', 'error');
            });
    });

    function generarReporte(id) {
        window.open(`/proveedores/${id}/reporte`, '_blank');
    }

    $(document).on('click', '.generar-reporte', function() {
        const id = $(this).data('id');
        window.open(`/proveedores/${id}/reporte`, '_blank');
    });
});