document.addEventListener("DOMContentLoaded", () => {
    const atelierCard = document.getElementById('atelierCard');
    const btnInscrire = document.getElementById('btnInscrire');
    const participantsSelect = document.getElementById('participantsSelect');

    document.querySelectorAll('.day').forEach(day => {
        day.addEventListener('click', () => {
            const atelierId = day.dataset.id;
            const titre = day.dataset.name || "Atelier de peinture";
            const niveau = day.dataset.level || "Tous niveaux";
            const description = day.dataset.description || "";
            const maxPlaces = parseInt(day.dataset.max || 10);
            const inscrits = parseInt(day.dataset.registered || 0);
            const date = day.dataset.date || "";

            // Remplissage du modal
            document.getElementById('atelierTitle').innerText = titre;
            document.getElementById('atelierLevel').innerText = "Niveau : " + niveau;
            document.getElementById('atelierDesc').innerText = description;
            document.getElementById('atelierDate').innerText = "Le " + date;

            const remaining = Math.max(0, maxPlaces - inscrits);
            document.getElementById('atelierSpots').innerText =
                `Il reste ${remaining} place${remaining > 1 ? 's' : ''} sur ${maxPlaces}`;

            // Réinitialiser bouton
            btnInscrire.className = "btn btn-primary";
            btnInscrire.disabled = false;
            btnInscrire.innerText = "Je participe à l'atelier !";

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

                            const newRegistered = inscrits + places;
                            day.dataset.registered = newRegistered;
                            const remainingNew = Math.max(0, maxPlaces - newRegistered);
                            document.getElementById('atelierSpots').innerText =
                                `Il reste ${remainingNew} place${remainingNew > 1 ? 's' : ''} sur ${maxPlaces}`;
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
