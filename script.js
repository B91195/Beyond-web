document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const closeModal = document.getElementById('closeModal');

    document.querySelectorAll('img').forEach(img => {
        img.addEventListener('click', () => {
            modal.style.display = 'block';
            modalImage.src = img.src;
        });
    });

    closeModal.addEventListener('click', () => {
        modal.style.display = 'none';
    });
});


    // Cerrar modal cuando se hace clic fuera de la imagen
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

function openModal(imageUrl) {
    var modal = document.createElement("div");
    modal.style.position = "fixed";
    modal.style.top = "0";
    modal.style.left = "0";
    modal.style.width = "100%";
    modal.style.height = "100%";
    modal.style.backgroundColor = "rgba(0, 0, 0, 0.8)";
    modal.style.display = "flex";
    modal.style.alignItems = "center";
    modal.style.justifyContent = "center";
    modal.style.zIndex = "1000";

    modal.onclick = function() {
        document.body.removeChild(modal);
    }

    var img = document.createElement("img");
    img.src = imageUrl;
    img.style.maxWidth = "90%";
    img.style.maxHeight = "90%";
    modal.appendChild(img);

    document.body.appendChild(modal);
}

    
