document.addEventListener('DOMContentLoaded', () => {

    // --- VARIABLES POUR LE MODAL ---
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');
    const modalSize = document.getElementById('modalSize');
    const modalDesc = document.getElementById('modalDescription');
    const closeBtn = document.querySelector('.close');

    // --- OUVERTURE DU MODAL + TRACKING DU CLIC ---
    document.querySelectorAll('.img-clickable').forEach(card => {
        card.addEventListener('click', e => {
            if (e.target.classList.contains('heart-icon')) return; // ignore clic sur cœur

            modal.style.display = 'flex';
            modalImg.src = card.querySelector('img').src;
            modalTitle.textContent = card.dataset.title;
            modalSize.textContent = "Taille : " + card.dataset.size;
            modalDesc.textContent = card.dataset.description;

            // Tracking du clic
            const tableauId = card.dataset.id;
            const tableauTitle = card.dataset.title;

            fetch('/track-click', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'tableauId=' + encodeURIComponent(tableauId) +
                    '&tableauTitle=' + encodeURIComponent(tableauTitle)
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        console.log('Clic enregistré sur ID:', tableauId, 'titre:', tableauTitle, 'à', data.clicked_at);
                    } else {
                        console.error('Erreur trackClick:', data.error);
                    }
                })
                .catch(err => console.error('Erreur fetch trackClick:', err));

        });
    });

    // --- FERMETURE DU MODAL ---
    closeBtn.addEventListener('click', () => modal.style.display = 'none');
    modal.addEventListener('click', e => {
        if (e.target === modal) modal.style.display = 'none';
    });

    // --- GESTION DES FAVORIS ---
    document.querySelectorAll('.heart-icon').forEach(icon => {
        icon.addEventListener('click', e => {
            e.stopPropagation(); // évite ouverture du modal

            const galleryId = icon.dataset.imageId;

            fetch('/toggle-favorite', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'galleryId=' + galleryId
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if (data.isFavorite) {
                            icon.classList.add('favorited');
                        } else {
                            icon.classList.remove('favorited');
                        }
                    } else {
                        console.error('Erreur toggleFavorite:', data.error);
                    }
                })
                .catch(err => console.error('Erreur fetch toggleFavorite:', err));
        });
    });

});
