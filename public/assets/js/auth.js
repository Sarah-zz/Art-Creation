document.addEventListener("DOMContentLoaded", () => {

    function handleForm(formId, messageId, url) {
        const form = document.getElementById(formId);
        if (!form) return;

        form.addEventListener("submit", (e) => {
            e.preventDefault();
            const formData = new FormData(form);

            fetch(url, { method: "POST", body: formData })
                .then(res => res.json())
                .then(data => {
                    const msg = document.getElementById(messageId);
                    if (!msg) return;
                    if (data.success) {
                        msg.classList.remove('text-danger');
                        msg.classList.add('text-success');
                        msg.innerText = "Succès ! Redirection...";
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 1300);
                    } else {
                        msg.classList.remove('text-success');
                        msg.classList.add('text-danger');
                        msg.innerText = data.errors ? data.errors.join("\n") : "Erreur inconnue";
                    }
                })
                .catch(err => {
                    console.error("Erreur fetch :", err);
                });
        });
    }

    // --- Login ---
    handleForm("loginForm", "loginMessage", "/login");

    // --- Register ---
    handleForm("registerForm", "registerMessage", "/register");

    // --- Logout ---
    const logoutBtn = document.getElementById("confirmLogoutBtn");
    if (logoutBtn) {
        logoutBtn.addEventListener("click", () => {
            fetch("/logout", { method: "POST" })
                .then(res => res.json())
                .then(data => {
                    console.log("Logout response:", data);
                    if (data.success) {
                        window.location.href = data.redirect;
                    }
                })
                .catch(err => console.error("Erreur logout:", err));
        });
    }

});
