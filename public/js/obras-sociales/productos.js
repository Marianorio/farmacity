let productosData = []; // Variable para almacenar todos los productos

function verProductos(id) {
    $.ajax({
        url: `/obras-sociales/${id}/productos`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                productosData = response.data; // Guardamos los datos
                $('#nombreObraSocial').text(response.obraSocial.nombre);
                cargarTablaProductos(productosData);
                $('#modalProductosCubiertos').modal('show');
            } else {
                Swal.fire('Error', response.message || 'No se pudieron cargar los productos', 'error');
            }
        },
        error: function(xhr) {
            console.error('Error:', xhr);
            Swal.fire('Error', 'No se pudieron cargar los productos', 'error');
        }
    });
}

function cargarTablaProductos(productos) {
    let html = '';
    
    if (productos && productos.length > 0) {
        productos.forEach(function(producto) {
            const precioOriginal = parseFloat(producto.precio);
            const descuento = producto.pivot.descuento;
            const precioFinal = precioOriginal * (1 - descuento/100);
            
            html += `
                <tr>
                    <td>${producto.codigo || '-'}</td>
                    <td>${producto.nombre}</td>
                    <td>${descuento}%</td>
                    <td>$${precioOriginal.toFixed(2)}</td>
                    <td>$${precioFinal.toFixed(2)}</td>
                </tr>
            `;
        });
    } else {
        html = '<tr><td colspan="5" class="text-center">No hay productos asociados</td></tr>';
    }
    
    $('#tablaProductosCubiertos tbody').html(html);
}

// Función de búsqueda
$(document).ready(function() {
    $('#buscarProducto').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();
        
        const productosFiltrados = productosData.filter(producto => 
            producto.nombre.toLowerCase().includes(searchTerm) || 
            (producto.codigo && producto.codigo.toLowerCase().includes(searchTerm))
        );
        
        cargarTablaProductos(productosFiltrados);
    });
});

// Opcional: Agregar ordenamiento a las columnas
$(document).ready(function() {
    $('#tablaProductosCubiertos').DataTable({
        "paging": false, // Desactivar paginación
        "searching": false, // Desactivar búsqueda integrada
        "info": false, // Desactivar información
        "order": [[1, 'asc']], // Ordenar por nombre por defecto
        "language": {
            "emptyTable": "No hay productos asociados",
            "zeroRecords": "No se encontraron coincidencias"
        }
    });
});