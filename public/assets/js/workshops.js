document.addEventListener("DOMContentLoaded", () => {
    const atelierCard = document.getElementById('atelierCard');
    const btnInscrire = document.getElementById('btnInscrire');
    const participantsSelect = document.getElementById('participantsSelect');

    // Variable définie côté PHP pour savoir si l'utilisateur est connecté
    const isLoggedIn = typeof window.isLoggedIn !== 'undefined' ? window.isLoggedIn : false;

    document.querySelectorAll('#calendar .day').forEach(day => {
        const maxPlaces = parseInt(day.dataset.max || 10);
        let registered = parseInt(day.dataset.registered || 0);
        let userRegistered = day.dataset.userRegistered === '1';

        // Mise à jour de l'affichage du jour et du bouton
        const updateDayUI = () => {
            const remaining = Math.max(0, maxPlaces - registered);
            const spotsEl = document.getElementById('atelierSpots');
            if (spotsEl) spotsEl.innerText = `Il reste ${remaining} place${remaining > 1 ? 's' : ''} sur ${maxPlaces}`;

            if (userRegistered) {
                btnInscrire.className = "btn btn-success";
                btnInscrire.innerText = "Inscription confirmée !";
                btnInscrire.disabled = true;
                day.classList.add('reserved');
                const dateEl = document.getElementById('atelierDate');
                if (dateEl) dateEl.style.color = "#999";
            } else {
                btnInscrire.className = "btn btn-primary";
                btnInscrire.innerText = "Je participe à l'atelier !";
                btnInscrire.disabled = false;
                const dateEl = document.getElementById('atelierDate');
                if (dateEl) dateEl.style.color = "#000";
            }
        };

        // Affichage initial si déjà inscrit
        if (isLoggedIn && userRegistered) {
            day.classList.add('reserved');
        }

        day.addEventListener('click', () => {
            const atelierId = day.dataset.id;
            const titre = day.dataset.name || "Atelier de peinture";
            const niveau = day.dataset.level || "Tous niveaux";
            const description = day.dataset.description || "";
            const date = day.dataset.date || "";

            document.getElementById('atelierTitle').innerText = titre;
            document.getElementById('atelierLevel').innerText = "Niveau : " + niveau;
            document.getElementById('atelierDesc').innerText = description;
            document.getElementById('atelierDate').innerText = "Le " + date;

            updateDayUI();

            const modal = new bootstrap.Modal(atelierCard);
            modal.show();

            btnInscrire.onclick = () => {
                if (!isLoggedIn) {
                    // Si pas connecté : afficher le modal de connexion
                    const authModalEl = document.getElementById('authModal');
                    if (authModalEl) {
                        const authModal = new bootstrap.Modal(authModalEl);
                        authModal.show();
                    }
                    modal.hide();
                    return;
                }

                // Si connecté : inscription directe
                const places = parseInt(participantsSelect.value || 1);

                fetch('/workshops/register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `workshop_id=${atelierId}&participants=${places}`
                })
                    .then(resp => resp.json())
                    .then(data => {
                        if (data.success) {
                            registered += places;
                            userRegistered = true;
                            day.dataset.registered = registered;
                            day.dataset.userRegistered = '1';
                            updateDayUI();
                        } else {
                            alert("Erreur inscription : " + (data.error || "Impossible d'inscrire"));
                        }
                    })
                    .catch(err => console.error('Erreur fetch inscription:', err));
            };
        });
    });

    // Nettoyage de l'URL après redirect
    if (window.history.replaceState && window.location.search.includes('redirect=')) {
        const cleanURL = window.location.protocol + "//" + window.location.host + window.location.pathname;
        window.history.replaceState(null, null, cleanURL);
    }
});
