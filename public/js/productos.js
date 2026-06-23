function cargarProveedores() {
    $.ajax({
        url: '/proveedores/lista',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                // Procesar la respuesta
                const proveedores = response.data;
                const select = $('#proveedor_id');
                select.empty();
                select.append('<option value="">Seleccione un proveedor</option>');
                proveedores.forEach(proveedor => {
                    select.append(`<option value="${proveedor.id}">${proveedor.nombre}</option>`);
                });
            } else {
                console.error('Error al cargar proveedores:', response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar proveedores:', {
                status: status,
                error: error,
                response: xhr.responseText
            });
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron cargar los proveedores'
            });
        }
    });
} 