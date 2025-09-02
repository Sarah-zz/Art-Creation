document.addEventListener("DOMContentLoaded", function () {
    // --- Login ---
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(loginForm);

            fetch("/login", { method: "POST", body: formData })
                .then(res => res.json())
                .then(data => {
                    const msg = document.getElementById("loginMessage");
                    if (data.success) {
                        msg.style.color = "green";
                        msg.innerText = "Connexion réussie, redirection...";
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 1500);
                    } else {
                        msg.style.color = "red";
                        msg.innerText = data.errors.join("\n");
                    }
                });
        });
    }

    // --- Register ---
    const registerForm = document.getElementById("registerForm");
    if (registerForm) {
        registerForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(registerForm);

            fetch("/register", { method: "POST", body: formData })
                .then(res => res.json())
                .then(data => {
                    const msg = document.getElementById("registerMessage");
                    if (data.success) {
                        msg.style.color = "green";
                        msg.innerText = "Inscription réussie, redirection...";
                        setTimeout(() => {
                            window.location.href = "/profil";
                        }, 1500);
                    } else {
                        msg.style.color = "red";
                        msg.innerText = data.errors.join("\n");
                    }
                });
        });
    }

    // --- Logout ---
    const logoutBtn = document.getElementById('confirmLogoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', () => {
            fetch('/logout', { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect;
                    }
                });
        });
    }
});
