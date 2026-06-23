$(document).ready(function() {
    $('#tabla-obras-sociales').DataTable({
        ajax: {
            url: '/obras-sociales/data',
            type: 'GET',
            error: function(xhr, error, thrown) {
                console.error('Error:', error);
            }
        },
        columns: [
            {data: 'id'},
            {data: 'nombre'},
            {data: 'cuit'},
            {data: 'fecha_convenio'},
            {data: 'fecha_vencimiento_convenio'},
            {
                data: 'id',
                orderable: false,
                render: function(data, type, row) {
                    return `
                        <div class="btn-group">
                            <button class="btn btn-success btn-sm ver-obra-social" data-id="${row.id}">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-info btn-sm productos-obra-social" data-id="${row.id}">
                                <i class="fas fa-pills"></i>
                            </button>
                            <button class="btn btn-primary btn-sm editar-obra-social" data-id="${row.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm eliminar-obra-social" data-id="${row.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        language: {
            "processing": "Procesando...",
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "emptyTable": "Ningún dato disponible en esta tabla",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "search": "Buscar:",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        }
    });

    // Evento para ver obra social
    $(document).on('click', '.ver-obra-social', function() {
        const obraId = $(this).data('id');
        
        fetch(`/obras-sociales/${obraId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const obra = data.data;
                    
                    Swal.fire({
                        title: '<i class="fas fa-hospital"></i> Detalles de la Obra Social',
                        html: `
                            <div class="text-start">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">${obra.nombre}</h5>
                                    </div>
                                </div>
                                
                                <table class="table table-sm table-borderless">
                                    <tbody>
                                        <tr>
                                            <td><strong>ID:</strong></td>
                                            <td><span class="badge bg-secondary">${obra.id}</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>CUIT:</strong></td>
                                            <td>${obra.cuit}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fecha Convenio:</strong></td>
                                            <td>${new Date(obra.fecha_convenio).toLocaleDateString('es-ES')}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fecha Vencimiento:</strong></td>
                                            <td>
                                                <span class="badge bg-${new Date(obra.fecha_vencimiento_convenio) < new Date() ? 'danger' : 'success'}">
                                                    ${new Date(obra.fecha_vencimiento_convenio).toLocaleDateString('es-ES')}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Código Validación:</strong></td>
                                            <td>${obra.codigo_validacion || 'N/A'}</td>
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
                Swal.fire('Error', 'No se pudo obtener los detalles de la obra social', 'error');
            });
    });

    // Evento para editar obra social
    $(document).on('click', '.editar-obra-social', function() {
        const obraId = $(this).data('id');
        
        fetch(`/obras-sociales/${obraId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const obra = data.data;
                    
                    $('#editar_id').val(obra.id);
                    $('#editar_nombre').val(obra.nombre);
                    $('#editar_cuit').val(obra.cuit);
                    $('#editar_fecha_convenio').val(obra.fecha_convenio);
                    $('#editar_fecha_vencimiento_convenio').val(obra.fecha_vencimiento_convenio);
                    $('#editar_codigo_validacion').val(obra.codigo_validacion);
                    
                    $('#modalEditarObraSocial').modal('show');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'No se pudo cargar la obra social', 'error');
            });
    });

    // Evento para ver productos de obra social
    $(document).on('click', '.productos-obra-social', function() {
        const obraId = $(this).data('id');
        
        fetch(`/obras-sociales/${obraId}/productos`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let productosHTML = '<p class="text-muted">No hay productos relacionados</p>';
                    
                    if (data.data && data.data.length > 0) {
                        productosHTML = `
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th>Descripción</th>
                                        <th>Descuento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${data.data.map(p => `
                                        <tr>
                                            <td><strong>${p.nombre}</strong></td>
                                            <td>${p.descripcion || 'N/A'}</td>
                                            <td><span class="badge bg-info">${p.descuento}%</span></td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        `;
                    }
                    
                    Swal.fire({
                        title: '<i class="fas fa-pills"></i> Productos Cubiertos',
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
                Swal.fire('Error', 'No se pudo obtener los productos', 'error');
            });
    });

    // Evento para eliminar obra social
    $(document).on('click', '.eliminar-obra-social', function() {
        const obraId = $(this).data('id');
        
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
                fetch(`/obras-sociales/${obraId}`, {
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
                    if (data.success) {
                        Swal.fire('Eliminado', 'La obra social ha sido eliminada', 'success');
                        $('#tabla-obras-sociales').DataTable().ajax.reload();
                    } else {
                        Swal.fire('Error', data.message || 'Error al eliminar la obra social', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Error al eliminar la obra social', 'error');
                });
            }
        });
    });
});

// Manejar el envío del formulario de edición
$('#formEditarObraSocial').submit(function(e) {
    e.preventDefault();
    let id = $('#editar_id').val();
    let formData = new FormData(this);
    formData.append('_method', 'PUT');
    
    $.ajax({
        url: `/obras-sociales/${id}`,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#modalEditarObraSocial').modal('hide');
            $('#tabla-obras-sociales').DataTable().ajax.reload();
            Swal.fire('¡Éxito!', 'Obra Social actualizada correctamente', 'success');
        },
        error: function(xhr) {
            let mensaje = xhr.responseJSON?.message || 'No se pudo actualizar la Obra Social';
            Swal.fire('Error', mensaje, 'error');
        }
    });
});

// Manejar el envío del formulario de nueva obra social
$('#formNuevaObraSocial').submit(function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    
    $.ajax({
        url: "/obras-sociales",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#formNuevaObraSocial')[0].reset();
                $('#modalNuevaObraSocial').modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('padding-right', '');
                $('#tabla-obras-sociales').DataTable().ajax.reload();
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Obra Social guardada correctamente'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Ocurrió un error al guardar la Obra Social'
                });
            }
        },
        error: function(xhr) {
            let mensaje = '';
            
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                mensaje = Object.values(xhr.responseJSON.errors).flat().join('\n');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                mensaje = xhr.responseJSON.message;
            } else {
                mensaje = 'No se pudo guardar la Obra Social';
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: mensaje
            });
        }
    });
});

// Limpiar modal cuando se cierra manualmente
$('#modalNuevaObraSocial').on('hidden.bs.modal', function () {
    $('#formNuevaObraSocial')[0].reset();
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open').css('padding-right', '');
});

// Validación del CUIT en tiempo real
$('#cuit').on('blur', function() {
    let cuit = $(this).val();
    
    $.ajax({
        url: "/obras-sociales/verificar-cuit",
        type: 'POST',
        data: {
            cuit: cuit,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (!response.disponible) {
                Swal.fire({
                    icon: 'warning',
                    title: 'CUIT duplicado',
                    text: 'Este CUIT ya está registrado'
                });
                $('#cuit').val('');
            }
        }
    });
});

$('#modalNuevaObraSocial').on('hidden.bs.modal', function() {
    $('#emergencyClose').hide();
});

// Función para ver productos
function verProductos(id) {
    $('#productos-body').html('<tr><td colspan="3">Cargando...</td></tr>');
    $('#modalProductosObraSocial').modal('show');

    $.ajax({
        url: '/obras-sociales/' + id + '/productos',
        method: 'GET',
        success: function(response) {
            let html = '';
            
            if (response.data.length === 0) {
                html = '<tr><td colspan="3">No hay productos asociados</td></tr>';
            } else {
                response.data.forEach(function(producto) {
                    html += `
                        <tr>
                            <td>${producto.nombre}</td>
                            <td>${producto.descripcion}</td>
                            <td>${producto.descuento}%</td>
                        </tr>
                    `;
                });
            }
            
            $('#productos-body').html(html);
        },
        error: function() {
            $('#productos-body').html('<tr><td colspan="3">Error al cargar los productos</td></tr>');
        }
    });
}

function generarReporte(id) {
    window.open(`/obras-sociales/${id}/reporte`, '_blank');
}




