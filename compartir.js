$(document).ready(function() {
    $('#copiar-enlace').click(function() {
        var enlaceInput = document.getElementById('enlace-compartido');
        enlaceInput.select();
        document.execCommand('copy');
        
        $('#mensaje-copiado').show().delay(2000).fadeOut();
    });
});
