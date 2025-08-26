document.addEventListener("DOMContentLoaded", () => {
    const atelierCard = document.getElementById('atelierCard');

    document.querySelectorAll('.day').forEach(day => {
        day.addEventListener('click', () => {
            document.getElementById('atelierTitle').innerText = "Atelier de peinture";
            document.getElementById('atelierLevel').innerText = "Tous niveaux";
            document.getElementById('atelierSpots').innerText = "Places disponibles : 8";
            document.getElementById('atelierHours').innerText = "Le " + day.dataset.date + " de 14h à 16h";

            const modal = new bootstrap.Modal(atelierCard);
            modal.show();

            const btn = document.getElementById('btnInscrire');
            btn.classList.remove('btn-success');
            btn.classList.add('btn-primary');
            btn.disabled = false;
            btn.innerText = "Je participe à l'atelier !";

            btn.onclick = () => {
                if (!isLoggedIn) {
                    const authModal = new bootstrap.Modal(document.getElementById('authModal'));
                    authModal.show();
                    modal.hide();
                } else {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-success');
                    btn.innerText = "Inscription pour l'atelier ok !";
                    btn.disabled = true;
                }
            };
        });
    });

    // Nettoyage URL si redirect
    if (window.history.replaceState && window.location.search.includes('redirect=')) {
        const cleanURL = window.location.protocol + "//" + window.location.host + window.location.pathname;
        window.history.replaceState(null, null, cleanURL);
    }
});
