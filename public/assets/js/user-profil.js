document.addEventListener('DOMContentLoaded', () => {
    const cancelButtons = document.querySelectorAll('.btn-cancel');
    

    cancelButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const workshopId = btn.dataset.workshopId;
            if (!workshopId || workshopId <= 0) return;

            if (!confirm('Voulez-vous vraiment annuler cette réservation ?')) return;

            fetch('/workshops/cancel', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ workshop_id: workshopId }),
                credentials: 'same-origin' // essentiel pour PHP session
            })
                .then(resp => resp.json())
                .then(data => {
                    console.log(data);
                    if (data.success) {
                        btn.className = 'btn btn-secondary';
                        btn.innerText = 'Réservation annulée';
                        btn.disabled = true;

                        const spotsEl = document.getElementById(`spots-${workshopId}`);
                        if (spotsEl) {
                            spotsEl.innerText = 'Nombre de participants réservés : ' + data.total_registered;
                        }
                    } else {
                        alert('Erreur lors de l\'annulation : ' + (data.error || 'Erreur inconnue'));
                    }
                })
                .catch(err => console.error('Erreur fetch annulation:', err));
        });
    });
});
