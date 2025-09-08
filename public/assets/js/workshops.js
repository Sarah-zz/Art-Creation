document.addEventListener("DOMContentLoaded", () => {
    const atelierCard = document.getElementById('atelierCard');
    const btnInscrire = document.getElementById('btnInscrire');
    const participantsSelect = document.getElementById('participantsSelect');

    document.querySelectorAll('#calendar .day').forEach(day => {
        const registered = parseInt(day.dataset.registered || 0);

        // --- Si l'utilisateur est déjà inscrit, marquer la date ---
        if (registered > 0) {
            day.classList.add('reserved');
            day.title = "Inscription confirmée !";
        }

        day.addEventListener('click', () => {
            const atelierId = day.dataset.id;
            const titre = day.dataset.name || "Atelier de peinture";
            const niveau = day.dataset.level || "Tous niveaux";
            const description = day.dataset.description || "";
            const maxPlaces = parseInt(day.dataset.max || 10);
            const date = day.dataset.date || "";
            const registered = parseInt(day.dataset.registered || 0);

            // Remplissage du modal
            document.getElementById('atelierTitle').innerText = titre;
            document.getElementById('atelierLevel').innerText = "Niveau : " + niveau;
            document.getElementById('atelierDesc').innerText = description;
            document.getElementById('atelierDate').innerText = "Le " + date;

            const remaining = Math.max(0, maxPlaces - registered);
            const spotsEl = document.getElementById('atelierSpots');
            spotsEl.innerText = `Il reste ${remaining} place${remaining > 1 ? 's' : ''} sur ${maxPlaces}`;

            // --- Bouton selon inscription ---
            if (registered > 0) {
                btnInscrire.className = "btn btn-success";
                btnInscrire.innerText = "Inscription confirmée !";
                btnInscrire.disabled = true;
                document.getElementById('atelierDate').style.color = "#999";
            } else {
                btnInscrire.className = "btn btn-primary";
                btnInscrire.innerText = "Je participe à l'atelier !";
                btnInscrire.disabled = false;
                document.getElementById('atelierDate').style.color = "#000";
            }

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
                            btnInscrire.className = "btn btn-success";
                            btnInscrire.innerText = "Inscription confirmée !";
                            btnInscrire.disabled = true;

                            const newRegistered = registered + places;
                            day.dataset.registered = newRegistered;

                            // Mettre à jour le nombre de places
                            const remainingNew = Math.max(0, maxPlaces - newRegistered);
                            spotsEl.innerText = `Il reste ${remainingNew} place${remainingNew > 1 ? 's' : ''} sur ${maxPlaces}`;

                            // Griser la date et ajouter style reserved
                            day.classList.add('reserved');
                            document.getElementById('atelierDate').style.color = "#999";
                            day.title = "Inscription confirmée !";
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
