let coberturas = [];

function nuevoProducto() {
    $('#formProducto')[0].reset();
    $('#id').val('');
    coberturas = []; // Limpiar coberturas
    actualizarTablaCoberturas();
    $('#modalProductoLabel').text('Nuevo Producto');
    $('#modalProducto').modal('show');
    
    cargarCategorias();
    cargarProveedores();
    cargarObrasSociales();
}

function cargarCategorias() {
    fetch('/categorias/lista')
        .then(response => {
            if (!response.ok) throw new Error('Error en la respuesta del servidor');
            return response.json();
        })
        .then(data => {
            const select = $('#id_categoria');
            select.empty().append('<option value="">Seleccione una categoría</option>');
            
            data.forEach(categoria => {
                select.append(new Option(categoria.nombre, categoria.id));
            });
            
            select.trigger('change');
        })
        .catch(error => {
            console.error('Error al cargar categorías:', error);
            Swal.fire('Error', 'No se pudieron cargar las categorías', 'error');
        });
}

function cargarProveedores() {
    fetch('/proveedores/lista')
        .then(response => {
            if (!response.ok) {
                console.log('Estado de la respuesta:', response.status);
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos de proveedores:', data); // Para debug
            const select = $('#id_proveedor');
            select.empty().append('<option value="">Seleccione un proveedor</option>');
            
            if (Array.isArray(data)) {
                data.forEach(proveedor => {
                    select.append(new Option(proveedor.nombre, proveedor.id));
                });
            } else {
                console.error('Formato de datos incorrecto:', data);
            }
            
            select.trigger('change');
        })
        .catch(error => {
            console.error('Error al cargar proveedores:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron cargar los proveedores: ' + error.message
            });
        });
}

function cargarObrasSociales() {
    fetch('/obras-sociales/lista')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos de obras sociales:', data); // Para debug
            const select = $('#id_obra_social');
            select.empty().append('<option value="">Seleccione una obra social</option>');
            
            if (Array.isArray(data)) {
                data.forEach(obraSocial => {
                    select.append(new Option(obraSocial.nombre, obraSocial.id));
                });
            } else {
                console.error('Formato de datos incorrecto:', data);
            }
            
            select.trigger('change');
        })
        .catch(error => {
            console.error('Error al cargar obras sociales:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron cargar las obras sociales'
            });
        });
}

// Evento para agregar cobertura
$(document).on('click', '#agregarCobertura', function(e) {
    e.preventDefault();
    
    const obraSocialId = $('#id_obra_social').val();
    const obraSocialText = $('#id_obra_social option:selected').text();
    const porcentaje = $('#porcentaje_cobertura').val();

    if (!obraSocialId || !porcentaje) {
        Swal.fire('Error', 'Debe seleccionar una obra social y un porcentaje', 'error');
        return;
    }

    // Verificar si ya existe
    if (coberturas.some(c => c.obraSocialId === obraSocialId)) {
        Swal.fire('Error', 'Esta obra social ya ha sido agregada', 'error');
        return;
    }

    // Agregar la cobertura
    coberturas.push({
        obraSocialId: obraSocialId,
        obraSocialNombre: obraSocialText,
        porcentaje: porcentaje
    });

    actualizarTablaCoberturas();
    
    // Limpiar campos
    $('#id_obra_social').val('').trigger('change');
    $('#porcentaje_cobertura').val('');
});

function actualizarTablaCoberturas() {
    const tabla = $('#tabla-coberturas');
    tabla.empty();

    coberturas.forEach((cobertura, index) => {
        tabla.append(`
            <tr>
                <td>${cobertura.obraSocialNombre}</td>
                <td>${cobertura.porcentaje}%</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarCobertura(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);
    });
}

function eliminarCobertura(index) {
    coberturas.splice(index, 1);
    actualizarTablaCoberturas();
}

// Modificar el evento submit del formulario
$('#formProducto').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('coberturas', JSON.stringify(coberturas));

    $.ajax({
        url: '/productos',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: response.message
                }).then(() => {
                    $('#modalProducto').modal('hide');
                    $('#tabla-productos').DataTable().ajax.reload();
                    coberturas = []; // Limpiar coberturas
                });
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function(xhr) {
            console.error('Error:', xhr);
            Swal.fire('Error', 'Hubo un error al guardar el producto', 'error');
        }
    });
});

$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });
    
    cargarCategorias();
    cargarProveedores();
    cargarObrasSociales();
    
    $('#btnNuevoProducto').on('click', nuevoProducto);
    
    if (!$.fn.DataTable.isDataTable('#tabla-productos')) {
        $('#tabla-productos').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/productos',
                error: function (xhr, error, thrown) {
                    console.error('Error en DataTables:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al cargar los productos'
                    });
                }
            },
            columns: [
                { data: 'id' },
                { data: 'nombre' },
                { data: 'descripcion' },
                { data: 'categoria.nombre' },
                { data: 'precio_compra' },
                { data: 'precio_venta' },
                { data: 'stock_inicial' },
                { data: 'stock_actual' },
                { data: 'stock_minimo' },
                { data: 'caducidad' },
                { 
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="btn-group">
                                <button class="btn btn-success btn-sm ver-producto" data-id="${row.id}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-primary btn-sm editar-producto" data-id="${row.id}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm eliminar-producto" data-id="${row.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            language: {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            }
        });
    }

    // Evento para ver producto
    $(document).on('click', '.ver-producto', function() {
        const productId = $(this).data('id');
        
        fetch(`/productos/${productId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const producto = data.producto;
                    
                    let coberturaHTML = '<p class="text-muted">Sin coberturas</p>';
                    if (data.coberturas && data.coberturas.length > 0) {
                        coberturaHTML = `
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Obra Social</th>
                                        <th>Descuento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${data.coberturas.map(c => `
                                        <tr>
                                            <td>${c.nombre}</td>
                                            <td><span class="badge bg-info">${c.porcentaje}%</span></td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        `;
                    }
                    
                    Swal.fire({
                        title: '<i class="fas fa-eye"></i> Detalles del Producto',
                        html: `
                            <div class="text-start">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">${producto.nombre}</h5>
                                        <p class="text-muted">${producto.descripcion || 'Sin descripción'}</p>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <table class="table table-sm table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td><strong>ID:</strong></td>
                                                    <td><span class="badge bg-secondary">${producto.id}</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Categoría:</strong></td>
                                                    <td>${producto.categoria?.nombre || 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Proveedor:</strong></td>
                                                    <td>${producto.proveedor?.nombre || 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Caducidad:</strong></td>
                                                    <td>${producto.caducidad ? new Date(producto.caducidad).toLocaleDateString('es-ES') : 'N/A'}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-sm table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td><strong>Precio Compra:</strong></td>
                                                    <td><span class="badge bg-warning text-dark">$${parseFloat(producto.precio_compra).toFixed(2)}</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Precio Venta:</strong></td>
                                                    <td><span class="badge bg-success">$${parseFloat(producto.precio_venta).toFixed(2)}</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Margen:</strong></td>
                                                    <td><span class="badge bg-info">${(((producto.precio_venta - producto.precio_compra) / producto.precio_compra * 100).toFixed(2))}%</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-boxes"></i> Stock</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-md-4">
                                                <div class="p-3 bg-light rounded">
                                                    <small class="text-muted d-block">Stock Inicial</small>
                                                    <h5>${producto.stock_inicial}</h5>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="p-3 bg-light rounded">
                                                    <small class="text-muted d-block">Stock Actual</small>
                                                    <h5 class="${producto.stock_actual < producto.stock_minimo ? 'text-danger' : 'text-success'}">${producto.stock_actual}</h5>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="p-3 bg-light rounded">
                                                    <small class="text-muted d-block">Stock Mínimo</small>
                                                    <h5 class="text-warning">${producto.stock_minimo}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-hospital"></i> Coberturas de Obras Sociales</h6>
                                    </div>
                                    <div class="card-body">
                                        ${coberturaHTML}
                                    </div>
                                </div>
                            </div>
                        `,
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
                Swal.fire('Error', 'No se pudo obtener los detalles del producto', 'error');
            });
    });

    // Evento para editar producto
    $(document).on('click', '.editar-producto', function() {
        const productId = $(this).data('id');
        
        fetch(`/productos/${productId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const producto = data.producto;
                    
                    $('#formProducto')[0].reset();
                    $('#id').val(producto.id);
                    $('#nombre').val(producto.nombre);
                    $('#descripcion').val(producto.descripcion);
                    $('#precio_compra').val(producto.precio_compra);
                    $('#precio_venta').val(producto.precio_venta);
                    $('#stock_inicial').val(producto.stock_inicial);
                    $('#stock_actual').val(producto.stock_actual);
                    $('#stock_minimo').val(producto.stock_minimo);
                    $('#caducidad').val(producto.caducidad);
                    
                    // Establecer categoría y proveedor
                    if (producto.id_categoria) {
                        $('#id_categoria').val(producto.id_categoria).trigger('change');
                    }
                    if (producto.id_proveedor) {
                        $('#id_proveedor').val(producto.id_proveedor).trigger('change');
                    }
                    
                    coberturas = data.coberturas || [];
                    actualizarTablaCoberturas();
                    
                    $('#modalProductoLabel').text('Editar Producto');
                    $('#modalProducto').modal('show');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'No se pudo cargar el producto', 'error');
            });
    });

    // Evento para eliminar producto
    $(document).on('click', '.eliminar-producto', function() {
        const productId = $(this).data('id');
        
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
                fetch(`/productos/${productId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Eliminado', 'El producto ha sido eliminado', 'success');
                        $('#tabla-productos').DataTable().ajax.reload();
                    } else {
                        Swal.fire('Error', data.message || 'Error al eliminar el producto', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Error al eliminar el producto', 'error');
                });
            }
        });
    });
});

