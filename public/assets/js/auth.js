document.addEventListener("DOMContentLoaded", function () {

    // Connexion
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(loginForm);
            fetch("/api/login.php", { method: "POST", body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.success) { location.reload(); }
                    else { document.getElementById("loginMessage").innerText = data.errors.join("\n"); }
                });
        });
    }

    // Inscription + connexion auto
    const registerForm = document.getElementById("registerForm");
    if (registerForm) {
        registerForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(registerForm);
            fetch("/api/register.php", { method: "POST", body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.success) { location.reload(); }
                    else { document.getElementById("registerMessage").innerText = data.errors.join("\n"); }
                });
        });
    }

    // Déconnexion
    const logoutBtn = document.getElementById('confirmLogoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', () => {
            fetch('/logout', {
                method: 'POST'
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        alert('Erreur lors de la déconnexion.');
                    }
                })
                .catch(() => {
                    alert('Erreur réseau ou serveur.');
                });
        });
    }
});