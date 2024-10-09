$(document).ready(function() {
    $('#form-comentario').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        
        $.ajax({
            url: 'agregar_comentario.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                var nuevoComentario = '<div class="comentario">' +
                    '<p><strong>' + response.nombre_usuario + '</strong> - ' + response.fecha_creacion + '</p>' +
                    '<p>' + response.contenido.replace(/\n/g, '<br>') + '</p>' +
                    '</div>';
                $('#lista-comentarios').prepend(nuevoComentario);
                $('#form-comentario textarea').val('');
            },
            error: function(xhr, status, error) {
                alert('Error al enviar el comentario: ' + error);
            }
        });
    });
});
