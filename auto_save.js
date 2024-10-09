let typingTimer;
const doneTypingInterval = 1000; // 1 second

document.querySelector('textarea[name="contenido"]').addEventListener('input', function() {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(saveDocument, doneTypingInterval);
});

function saveDocument() {
    const content = document.querySelector('textarea[name="contenido"]').value;
    const documentId = new URLSearchParams(window.location.search).get('id');

    fetch('guardar_documento.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id=' + documentId + '&contenido=' + encodeURIComponent(content)
    })
    .then(response => response.text())
    .then(data => {
        console.log('Auto-guardado exitoso:', data);
    })
    .catch((error) => {
        console.error('Error en auto-guardado:', error);
    });
}
