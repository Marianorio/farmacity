document.addEventListener('DOMContentLoaded', function() {
    // Cargar Obras Sociales
    function cargarObrasSociales() {
        $.ajax({
            url: '/reportes/obras-sociales/lista',
            type: 'GET',
            success: function(response) {
                console.log('Obras Sociales:', response);
                let options = '<option value="">Todas las Obras Sociales</option>';
                response.forEach(function(obraSocial) {
                    options += `<option value="${obraSocial.id}">${obraSocial.nombre}</option>`;
                });
                $('#obra_social_id').html(options);
                
                // Inicializar Select2 para obras sociales
                $('#obra_social_id').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: 'Seleccione una Obra Social'
                });
            },
            error: function(xhr) {
                console.error('Error completo:', xhr);
                Swal.fire('Error', 'No se pudieron cargar las obras sociales', 'error');
            }
        });
    }

    // Cargar Proveedores
    function cargarProveedores() {
        $.ajax({
            url: '/reportes/proveedores/lista',
            type: 'GET',
            success: function(response) {
                console.log('Proveedores:', response);
                let options = '<option value="">Todos los Proveedores</option>';
                response.forEach(function(proveedor) {
                    options += `<option value="${proveedor.id}">${proveedor.nombre}</option>`;
                });
                $('#proveedor_id').html(options);
                
                // Inicializar Select2 para proveedores
                $('#proveedor_id').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: 'Seleccione un Proveedor'
                });
            },
            error: function(xhr) {
                console.error('Error completo:', xhr);
                Swal.fire('Error', 'No se pudieron cargar los proveedores', 'error');
            }
        });
    }

    // Llamar a las funciones de carga cuando se carga la página
    cargarObrasSociales();
    cargarProveedores();

    // Inicializar DataTable con configuración en español
    if ($('#reportesTable').length > 0) {
        $('#reportesTable').DataTable({
            language: {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sSearch":         "Buscar:",
                "sUrl":           "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                }
            },
            // Por ahora, sin datos
            data: [],
            columns: [
                { data: 'titulo' },
                { data: 'tipo' },
                { data: 'periodo' },
                { data: 'generado_por' },
                { data: 'fecha_generacion' },
                { data: 'acciones' }
            ]
        });
    }

    // Manejar el envío de formularios de reportes
    $('.reporte-form').on('submit', function(e) {
        e.preventDefault(); // Prevenir el envío normal del formulario
        
        const form = $(this);
        const formData = new FormData(this);
        
        // Mostrar indicador de carga
        Swal.fire({
            title: 'Generando reporte...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Realizar la petición AJAX
        $.ajax({
            url: '/reportes/generar',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhrFields: {
                responseType: 'blob' // Para manejar la respuesta como PDF
            },
            success: function(response) {
                Swal.close();
                
                // Crear blob y URL
                const blob = new Blob([response], { type: 'application/pdf' });
                const url = window.URL.createObjectURL(blob);
                
                // Abrir PDF en nueva pestaña
                window.open(url, '_blank');
                
                // Mostrar mensaje de éxito
                Swal.fire({
                    icon: 'success',
                    title: '¡Reporte generado!',
                    text: 'El reporte se ha generado correctamente'
                });
            },
            error: function(xhr) {
                Swal.close();
                
                // Mostrar mensaje de error
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo generar el reporte. Por favor, intente nuevamente.'
                });
                
                console.error('Error completo:', xhr);
            }
        });
    });

    // Ver reporte
    $(document).on('click', '.ver-reporte', function() {
        const id = $(this).data('id');
        
        fetch(`/reportes/${id}`)
            .then(response => response.json())
            .then(data => {
                // Aquí puedes mostrar los datos en un modal o en una nueva ventana
                console.log('Datos del reporte:', data);
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo cargar el reporte',
                    icon: 'error'
                });
            });
    });

    // Descargar reporte
    $(document).on('click', '.descargar-reporte', function() {
        const archivo = $(this).data('archivo');
        window.location.href = `/reportes/descargar/${archivo}`;
    });

    // Actualizar fechas automáticamente al cambiar el tipo
    const tipoSelect = document.getElementById('tipo');
    if (tipoSelect) {
        tipoSelect.addEventListener('change', function() {
            const fechaFin = new Date();
            const fechaInicio = new Date();
            
            // Por defecto, último mes
            fechaInicio.setMonth(fechaFin.getMonth() - 1);
            
            // Actualizar las fechas en el formulario
            document.getElementById('fecha_inicio').value = fechaInicio.toISOString().split('T')[0];
            document.getElementById('fecha_fin').value = fechaFin.toISOString().split('T')[0];
        });
    }

    function generarReporteProveedor() {
        let proveedorId = $('#proveedor_id').val();
        
        console.log('ID del proveedor seleccionado:', proveedorId); // Para debug

        if (!proveedorId) {
            alert('Por favor seleccione un proveedor');
            return;
        }

        $.ajax({
            url: '/reportes/proveedor',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                proveedor_id: proveedorId,
                tipo: 'proveedor' // Agregamos el tipo de reporte
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response) {
                const blob = new Blob([response], { type: 'application/pdf' });
                const url = window.URL.createObjectURL(blob);
                window.open(url);
            },
            error: function(xhr) {
                console.error('Error completo:', xhr);
                alert('Error al generar el reporte');
            }
        });
    }

    // Agregar el evento al botón
    $(document).ready(function() {
        $('#btnGenerarReporteProveedor').click(function(e) {
            e.preventDefault();
            generarReporteProveedor();
        });
    });
});
