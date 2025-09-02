document.addEventListener('DOMContentLoaded', function () {

    //s'inscrire
    const registerForm = document.getElementById('registerForm');
    const registerMessage = document.getElementById('registerMessage');

    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            e.preventDefault(); // Empêche la page de recharger

            const formData = new FormData(registerForm); // Récupère les données du formulaire

            fetch('/ArtCreation/register', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        registerMessage.style.color = 'green';
                        registerMessage.textContent = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
                        registerForm.reset();
                    } else {
                        registerMessage.style.color = 'red';
                        registerMessage.textContent = data.errors ? data.errors.join(' ') : 'Erreur lors de l’inscription.';
                    }
                })
                .catch(() => {
                    registerMessage.style.color = 'red';
                    registerMessage.textContent = 'Erreur réseau ou serveur.';
                });
        });
    }

    // --- CONNEXION ---
    const loginForm = document.getElementById('loginForm');
    const loginMessage = document.getElementById('loginMessage');

    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(loginForm);

            fetch('/ArtCreation/login', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loginMessage.style.color = 'green';
                        loginMessage.textContent = 'Connexion réussie !';
                        loginForm.reset();
                        setTimeout(() => window.location.reload(), 800); // Recharge la page
                    } else {
                        loginMessage.style.color = 'red';
                        loginMessage.textContent = data.errors ? data.errors.join(' ') : 'Erreur lors de la connexion.';
                    }
                })
                .catch(() => {
                    loginMessage.style.color = 'red';
                    loginMessage.textContent = 'Erreur réseau ou serveur.';
                });
        });
    }

    // --- DECONNEXION ---
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function (e) {
            e.preventDefault();
            fetch('/ArtCreation/logout', { method: 'POST' })
                .then(() => window.location.reload()) // Recharge la page
                .catch(() => alert('Erreur lors de la déconnexion.'));
        });
    }

});
