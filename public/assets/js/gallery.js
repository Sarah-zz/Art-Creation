document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');
    const modalSize = document.getElementById('modalSize');
    const modalDesc = document.getElementById('modalDescription');
    const closeBtn = document.querySelector('.close');

    document.querySelectorAll('.img-clickable').forEach(card => {
        card.addEventListener('click', e => {

            // Si clic sur le cœur, ne pas ouvrir le modal ni tracker
            if (e.target.classList.contains('heart-icon')) return;

            const tableauId = card.dataset.id;
            const tableauTitle = card.dataset.title;
            const tableauSize = card.dataset.size || '';
            const tableauDesc = card.dataset.description || '';

            modal.style.display = 'flex';
            modalImg.src = card.querySelector('img').src;
            modalTitle.textContent = tableauTitle;
            modalSize.textContent = "Taille : " + tableauSize;
            modalDesc.textContent = tableauDesc;

            fetch('/track-click', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'tableauId=' + tableauId + '&tableauTitle=' + encodeURIComponent(tableauTitle)
            })
                .then(response => response.json())
                .then(data => console.log('Clic enregistré:', data))
                .catch(err => console.error('Erreur trackClick:', err));
        });
    });

    // Gestion clic cœur pour favoris
    document.querySelectorAll('.heart-icon').forEach(icon => {
        icon.addEventListener('click', e => {
            e.stopPropagation(); // empêche le clic de la card
            icon.classList.toggle('favorited');
        });
    });

    closeBtn.addEventListener('click', () => modal.style.display = 'none');
    modal.addEventListener('click', e => { if (e.target === modal) modal.style.display = 'none'; });
});
