document.addEventListener('DOMContentLoaded', () => {

    // Modal
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');
    const modalSize = document.getElementById('modalSize');
    const modalDesc = document.getElementById('modalDescription');
    const closeBtn = document.querySelector('.close');

    document.querySelectorAll('.img-clickable').forEach(card => {
        card.addEventListener('click', e => {
            if (e.target.classList.contains('heart-icon')) return; // ignore clic sur cœur

            modal.style.display = 'flex';
            modalImg.src = card.querySelector('img').src;
            modalTitle.textContent = card.dataset.title;
            modalSize.textContent = "Taille : " + card.dataset.size;
            modalDesc.textContent = card.dataset.description;
        });
    });

    closeBtn.addEventListener('click', () => modal.style.display = 'none');
    modal.addEventListener('click', e => { if (e.target === modal) modal.style.display = 'none'; });

    // Coeurs favoris
    document.querySelectorAll('.heart-icon').forEach(icon => {
        icon.addEventListener('click', e => {
            e.stopPropagation();
            icon.classList.toggle('favorited');
        });
    });

});
