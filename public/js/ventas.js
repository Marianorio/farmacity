// public/js/ventas.js

// Variables globales
let carrito = [];
let total = 0;
let subtotal = 0;
let impuestos = 0;
let descuentos = 0;

// Función principal para actualizar la vista del carrito
function actualizarVistaCarrito() {
    const listaCarrito = $('#listaCarrito');
    listaCarrito.empty();
    
    carrito.forEach(item => {
        const subtotalItem = item.precio_final * item.cantidad;
        
        listaCarrito.append(`
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-6">
                        ${item.nombre}
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control form-control-sm cantidad-producto" 
                               value="${item.cantidad}" min="1" data-id="${item.id}">
                    </div>
                    <div class="col-md-2">
                        $${subtotalItem.toFixed(2)}
                        ${item.descuento > 0 ? `<br><small class="text-success">-${item.descuento}%</small>` : ''}
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-danger btn-sm eliminar-producto" data-id="${item.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </li>
        `);
    });

    calcularTotales();
}

// Función para filtrar productos
function filtrarProductos() {
    $.ajax({
        url: '/ventas/buscar-productos',
        method: 'POST',
        data: {
            query: $('#buscadorProductos').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            const listaProductos = $('#listaProductos');
            listaProductos.empty();

            if (response && response.length > 0) {
                response.forEach(producto => {
                    listaProductos.append(`
                        <li class="list-group-item producto-item" 
                            data-id="${producto.id}" 
                            data-nombre="${producto.nombre}"
                            data-precio="${producto.precio}">
                            ${producto.nombre} - Stock: ${producto.stock_actual} - $${producto.precio}
                        </li>
                    `);
                });
                listaProductos.show();
            } else {
                listaProductos.html('<li class="list-group-item">No se encontraron productos</li>');
                listaProductos.show();
            }
        },
        error: function(xhr) {
            console.log('Error en la búsqueda:', xhr);
        }
    });
}

// Agregar el evento de búsqueda con debounce
let timeoutId;
$('#buscadorProductos').on('input', function() {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(filtrarProductos, 300);
});

// Evento para los productos
$(document).on('click', '.producto-item', function() {
    const id = $(this).data('id');
    const nombre = $(this).data('nombre');
    const precio = parseFloat($(this).data('precio'));
    
    // Verificar si el producto ya está en el carrito
    const productoExistente = carrito.find(item => item.id === id);
    
    if (productoExistente) {
        productoExistente.cantidad++;
    } else {
        // Si hay una obra social seleccionada, verificar cobertura
        const obraSocial = $('#obraSocial').val();
        const codigoValidacion = $('#codigoValidacion').val();
        
        if (obraSocial && codigoValidacion) {
            $.ajax({
                url: '/ventas/verificar-cobertura',
                method: 'POST',
                data: {
                    producto_id: id,
                    codigo_validacion: codigoValidacion,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    agregarProductoAlCarrito(id, nombre, precio, response.descuento || 0);
                },
                error: function(xhr) {
                    console.error('Error al verificar cobertura:', xhr);
                    agregarProductoAlCarrito(id, nombre, precio, 0);
                }
            });
        } else {
            agregarProductoAlCarrito(id, nombre, precio, 0);
        }
    }
    
    // Limpiar y ocultar la lista de búsqueda
    $('#buscadorProductos').val('');
    $('#listaProductos').empty().hide();
    
    actualizarVistaCarrito();
});

// Función auxiliar para agregar producto al carrito
function agregarProductoAlCarrito(id, nombre, precio, descuento = 0) {
    carrito.push({
        id: id,
        nombre: nombre,
        cantidad: 1,
        precio: precio,
        precio_final: precio * (1 - descuento / 100),
        descuento: descuento
    });
    actualizarVistaCarrito();
}

// Inicialización cuando el documento está listo
$(document).ready(function() {
    // Evento para el modal de nueva venta
    $('#nuevaVentaModal').on('show.bs.modal', function () {
        carrito = [];
        obtenerProximoNumeroCliente();
        obtenerProximoNumeroVenta();
        establecerFechaActual();
        actualizarVistaCarrito();
    });

    // Inicializar DataTable
    $('#tablaPrincipal').DataTable();

    // Eventos para el carrito
    $(document).on('change', '.cantidad-producto', function() {
        const id = $(this).data('id');
        const cantidad = parseInt($(this).val());
        
        if (cantidad < 1) {
            $(this).val(1);
            return;
        }
        
        const item = carrito.find(item => item.id === id);
        if (item) {
            item.cantidad = cantidad;
            actualizarVistaCarrito();
        }
    });

    $(document).on('click', '.eliminar-producto', function() {
        const productoId = $(this).data('id');
        carrito = carrito.filter(item => item.id !== productoId);
        actualizarVistaCarrito();
    });

    // Evento para guardar la venta
    $('#guardarVenta').on('click', function() {
        if (carrito.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Debe agregar al menos un producto al carrito'
            });
            return;
        }

        const ventaData = {
            productos: carrito.map(item => ({
                id: item.id,
                cantidad: item.cantidad,
                precio: item.precio,
                descuento: item.descuento
            })),
            subtotal: parseFloat($('#subtotal').val()),
            impuestos: parseFloat($('#impuestos').val()),
            descuento: parseFloat($('#descuentos').val()),
            total: parseFloat($('#totalAPagar').val()),
            metodo_pago: $('#metodoPago').val(),
            id_obra_social: $('#obraSocial').val() || null,
            codigo_validacion: $('#codigoValidacion').val() || null
        };

        $.ajax({
            url: '/ventas',
            type: 'POST',
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify(ventaData),
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Venta registrada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.open(`/ventas/${response.venta_id}/pdf`, '_blank');
                        $('#nuevaVentaModal').modal('hide');
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Error al registrar la venta'
                    });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    // Mostrar errores de validación en el modal
                    const errors = xhr.responseJSON.errors;
                    let html = '<ul class="mb-0">';
                    Object.keys(errors).forEach(function (key) {
                        errors[key].forEach(function(msg) {
                            html += `<li>${msg}</li>`;
                        });
                    });
                    html += '</ul>';
                    $('#ventaErrors').html(html).show();

                    // Agregar clase is-invalid a campos relacionados si existen
                    if (errors['productos']) {
                        // no specific input to mark, show generic highlight
                        $('#listaCarrito').addClass('border border-danger');
                    }
                    if (errors['subtotal'] || errors['total']) {
                        $('#subtotal, #totalAPagar').addClass('is-invalid');
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al procesar la venta: ' + (xhr.responseJSON?.message || 'Error desconocido')
                    });
                }
            }
        });
    });

    // Evento para ver detalles de la venta
    $(document).on('click', '.ver-venta', function() {
        const ventaId = $(this).data('id');
        
        $.ajax({
            url: `/ventas/${ventaId}`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const venta = response.venta;
                    const detalles = response.detalles;
                    
                    let detallesHtml = '';
                    detalles.forEach(detalle => {
                        detallesHtml += `
                            <tr>
                                <td>${detalle.producto.nombre}</td>
                                <td>${detalle.cantidad}</td>
                                <td>$${parseFloat(detalle.precio_unitario).toFixed(2)}</td>
                                <td>${detalle.descuento}%</td>
                                <td>$${parseFloat(detalle.subtotal).toFixed(2)}</td>
                            </tr>
                        `;
                    });

                    Swal.fire({
                        title: `Venta #${venta.id}`,
                        html: `
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>Precio</th>
                                            <th>Descuento</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${detallesHtml}
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class="text-right"><strong>Subtotal:</strong></td>
                                            <td>$${parseFloat(venta.subtotal).toFixed(2)}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-right"><strong>Impuestos:</strong></td>
                                            <td>$${parseFloat(venta.impuestos).toFixed(2)}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-right"><strong>Descuentos:</strong></td>
                                            <td>$${parseFloat(venta.descuento).toFixed(2)}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-right"><strong>Total:</strong></td>
                                            <td>$${parseFloat(venta.total).toFixed(2)}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="mt-3">
                                <p><strong>Fecha:</strong> ${moment(venta.created_at).format('DD/MM/YYYY HH:mm')}</p>
                                <p><strong>Estado:</strong> ${venta.estado}</p>
                                ${venta.estado === 'ANULADA' ? 
                                    `<p><strong>Motivo anulación:</strong> ${venta.motivo_anulacion || 'No especificado'}</p>` : ''}
                                ${venta.obra_social ? 
                                    `<p><strong>Obra Social:</strong> ${venta.obra_social.nombre}</p>` : ''}
                            </div>
                        `,
                        width: '800px'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Error al cargar los detalles de la venta'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar los detalles de la venta'
                });
            }
        });
    });

    // Evento para anular venta
    $(document).on('click', '.anular-venta', function() {
        const ventaId = $(this).data('id');
        
        Swal.fire({
            title: '¿Está seguro de anular esta venta?',
            text: 'Por favor, ingrese el motivo de la anulación:',
            input: 'textarea',
            inputAttributes: {
                required: true,
                minlength: 10
            },
            showCancelButton: true,
            confirmButtonText: 'Sí, anular',
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
            preConfirm: (motivoAnulacion) => {
                if (motivoAnulacion.length < 10) {
                    Swal.showValidationMessage('El motivo debe tener al menos 10 caracteres');
                    return false;
                }
                
                return $.ajax({
                    url: `/ventas/${ventaId}/anular`,
                    type: 'POST',
                    data: {
                        motivo_anulacion: motivoAnulacion,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    }
                }).catch(error => {
                    Swal.showValidationMessage(
                        `Error al anular la venta: ${error.responseJSON?.message || 'Error desconocido'}`
                    );
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Venta anulada',
                    text: 'La venta ha sido anulada correctamente',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.reload();
                });
            }
        });
    });

    // Evento para el botón de reporte de ventas
    $('#btnReporteVentas').on('click', function() {
        Swal.fire({
            title: 'Generar Reporte de Ventas',
            html: `
                <div class="form-group mb-3">
                    <label for="fechaInicio" class="form-label">Fecha Inicio</label>
                    <input type="date" id="fechaInicio" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label for="fechaFin" class="form-label">Fecha Fin</label>
                    <input type="date" id="fechaFin" class="form-control" required>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Generar',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                const fechaInicio = document.getElementById('fechaInicio').value;
                const fechaFin = document.getElementById('fechaFin').value;
                
                if (!fechaInicio || !fechaFin) {
                    Swal.showValidationMessage('Ambas fechas son requeridas');
                    return false;
                }

                // Crear una URL con los parámetros
                const url = `/ventas/reporte?fechaInicio=${fechaInicio}&fechaFin=${fechaFin}`;
                
                // Abrir el PDF en una nueva pestaña
                window.open(url, '_blank');
                return false; // Para cerrar el modal de SweetAlert2
            }
        });
    });

    // Ocultar el contenedor de código de validación al inicio
    $('#codigoValidacionContainer').hide();
    
    // Manejar el cambio en el select de obra social
    $('#obraSocial').on('change', function() {
        toggleCodigoValidacion();
    });

    $('#codigoValidacion').on('blur', function() {
        const codigo = $(this).val().trim();
        const idObraSocial = $('#obraSocial').val();
        const statusElement = $('#codigoValidacionStatus');
        
        if (!codigo || !idObraSocial) {
            statusElement.html('');
            return;
        }

        statusElement.html('<i class="fas fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: '/ventas/validar-codigo',
            type: 'POST',
            data: {
                codigo: codigo,
                id_obra_social: idObraSocial,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Respuesta del servidor:', response); // Para debug
                
                if (response.valid || response.valido) {
                    statusElement.html('<i class="fas fa-check text-success"></i>');
                    $('#codigoValidacion')
                        .removeClass('is-invalid')
                        .addClass('is-valid')
                        .attr('data-validado', 'true');
                } else {
                    statusElement.html('<i class="fas fa-times text-danger"></i>');
                    $('#codigoValidacion')
                        .removeClass('is-valid')
                        .addClass('is-invalid')
                        .attr('data-validado', 'false');
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Código Inválido',
                        text: response.message || 'El código de validación no es válido'
                    });
                }
            },
            error: function(xhr) {
                statusElement.html('<i class="fas fa-times text-danger"></i>');
                $('#codigoValidacion')
                    .removeClass('is-valid')
                    .addClass('is-invalid')
                    .attr('data-validado', 'false');
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Error al validar el código'
                });
            }
        });
    });
});

// Otras funciones necesarias
function calcularTotales() {
    let subtotal = 0;
    let impuestos = 0;
    let descuentos = 0;

    carrito.forEach(item => {
        const subtotalItem = item.precio * item.cantidad;
        subtotal += subtotalItem;
        
        if (item.descuento > 0) {
            const descuentoItem = subtotalItem * (item.descuento / 100);
            descuentos += descuentoItem;
        }
    });

    impuestos = subtotal * 0.21;
    const total = subtotal + impuestos - descuentos;

    $('#subtotal').val(subtotal.toFixed(2));
    $('#impuestos').val(impuestos.toFixed(2));
    $('#descuentos').val(descuentos.toFixed(2));
    $('#totalAPagar').val(total.toFixed(2));
}

function establecerFechaActual() {
    const ahora = moment().format('DD/MM/YYYY HH:mm');
    $('#fechaVenta').val(ahora);
}

function obtenerProximoNumeroCliente() {
    $.ajax({
        url: '/ventas/proximo-numero-cliente',
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#numeroCliente').val(response.numero_cliente);
            }
        },
        error: function(xhr) {
            console.error('Error al obtener número de cliente:', xhr);
        }
    });
}

function obtenerProximoNumeroVenta() {
    $.ajax({
        url: '/ventas/proximo-numero-venta',
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#numeroVenta').val(response.numero_venta);
            }
        },
        error: function(xhr) {
            console.error('Error al obtener número de venta:', xhr);
        }
    });
}

function toggleCodigoValidacion() {
    const obraSocial = document.getElementById('obraSocial').value;
    const codigoValidacionContainer = document.getElementById('codigoValidacionContainer');
    const codigoValidacion = document.getElementById('codigoValidacion');
    const statusElement = document.getElementById('codigoValidacionStatus');
    
    if (obraSocial) {
        codigoValidacionContainer.style.display = 'block';
        codigoValidacion.value = ''; // Limpiar el código
        codigoValidacion.classList.remove('is-valid', 'is-invalid');
        statusElement.innerHTML = ''; // Limpiar el estado
        codigoValidacion.setAttribute('required', 'required'); // Hacer el campo requerido
    } else {
        codigoValidacionContainer.style.display = 'none';
        codigoValidacion.removeAttribute('required');
    }
}

function mostrarReporte(response) {
    Swal.fire({
        title: 'Reporte de Ventas',
        html: `
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>N° Cliente</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${response.ventas.map(venta => `
                            <tr>
                                <td>${venta.numero_cliente}</td>
                                <td>${moment(venta.fecha).format('DD/MM/YYYY')}</td>
                                <td>$${parseFloat(venta.total).toFixed(2)}</td>
                                <td>${venta.estado}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"><strong>Total:</strong></td>
                            <td colspan="2"><strong>$${parseFloat(response.total).toFixed(2)}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="mt-3">
                <button class="btn btn-primary" onclick="generarPDFReporte('${response.fechaInicio}', '${response.fechaFin}')">
                    <i class="fas fa-file-pdf"></i> Descargar PDF
                </button>
            </div>
        `,
        width: '800px'
    });
}

function generarPDFReporte(fechaInicio, fechaFin) {
    Swal.fire({
        title: 'Generando PDF...',
        text: 'Por favor espere...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Crear la URL con los parámetros
    const url = `/ventas/reporte/pdf?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
    
    // Abrir en una nueva pestaña
    window.open(url, '_blank');
    
    // Cerrar el loading después de un momento
    setTimeout(() => {
        Swal.close();
    }, 1500);
}

function anularVenta(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción anulará la venta y restaurará el stock de los productos",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, anular',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/ventas/${id}/anular`,
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire(
                            '¡Anulada!',
                            'La venta ha sido anulada.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Error',
                            response.message,
                            'error'
                        );
                    }
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error',
                        'Error al anular la venta: ' + (xhr.responseJSON?.message || 'Error desconocido'),
                        'error'
                    );
                }
            });
        }
    });
}
