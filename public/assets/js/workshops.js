document.addEventListener("DOMContentLoaded", () => {
    const atelierCard = document.getElementById('atelierCard');
    const btnInscrire = document.getElementById('btnInscrire');
    const participantsSelect = document.getElementById('participantsSelect');

    document.querySelectorAll('#calendar .day').forEach(day => {
        const maxPlaces = parseInt(day.dataset.max || 10);
        let registered = parseInt(day.dataset.registered || 0);
        let userRegistered = day.dataset.userRegistered === '1';

        const updateDayUI = () => {
            const remaining = Math.max(0, maxPlaces - registered);
            const spotsEl = document.getElementById('atelierSpots');
            spotsEl.innerText = `Il reste ${remaining} place${remaining > 1 ? 's' : ''} sur ${maxPlaces}`;

            if (userRegistered) {
                btnInscrire.className = "btn btn-success";
                btnInscrire.innerText = "Inscription confirmée !";
                btnInscrire.disabled = true;
                day.classList.add('reserved');
                document.getElementById('atelierDate').style.color = "#999";
                day.title = "Inscription confirmée !";
            } else {
                btnInscrire.className = "btn btn-primary";
                btnInscrire.innerText = "Je participe à l'atelier !";
                btnInscrire.disabled = false;
                document.getElementById('atelierDate').style.color = "#000";
                day.title = "";
            }
        };

        // Initial UI update
        if (isLoggedIn && userRegistered) {
            day.classList.add('reserved');
            day.title = "Inscription confirmée !";
        }

        day.addEventListener('click', () => {
            const atelierId = day.dataset.id;
            const titre = day.dataset.name || "Atelier de peinture";
            const niveau = day.dataset.level || "Tous niveaux";
            const description = day.dataset.description || "";
            const date = day.dataset.date || "";

            // Remplissage du modal
            document.getElementById('atelierTitle').innerText = titre;
            document.getElementById('atelierLevel').innerText = "Niveau : " + niveau;
            document.getElementById('atelierDesc').innerText = description;
            document.getElementById('atelierDate').innerText = "Le " + date;

            updateDayUI();

            // Affiche modal
            const modal = new bootstrap.Modal(atelierCard);
            modal.show();

            // Clic inscription
            btnInscrire.onclick = () => {
                if (!isLoggedIn) {
                    const authModal = new bootstrap.Modal(document.getElementById('authModal'));
                    authModal.show();
                    modal.hide();
                    return;
                }

                const places = parseInt(participantsSelect.value);

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
                            alert("Erreur inscription : " + data.error);
                        }
                    })
                    .catch(err => console.error('Erreur fetch inscription:', err));
            };
        });
    });

    // Nettoyage URL si redirect
    if (window.history.replaceState && window.location.search.includes('redirect=')) {
        const cleanURL = window.location.protocol + "//" + window.location.host + window.location.pathname;
        window.history.replaceState(null, null, cleanURL);
    }
});
