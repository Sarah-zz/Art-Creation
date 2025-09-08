<?php if (empty($_SESSION['user'])): ?>
    <!-- Modal Connexion -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Connexion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <form id="loginForm">
                        <input type="text" name="identifier" class="form-control mb-2" placeholder="Email ou Pseudo"
                            required>
                        <input type="password" name="password" class="form-control mb-2" placeholder="Mot de passe"
                            required>
                        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                    </form>
                    <div id="loginMessage" class="mt-2 text-danger"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Inscription -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Inscription</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <form id="registerForm">
                        <input type="text" name="pseudo" class="form-control mb-2" placeholder="Pseudo" required>
                        <input type="text" name="firstname" class="form-control mb-2" placeholder="Prénom" required>
                        <input type="text" name="lastname" class="form-control mb-2" placeholder="Nom" required>
                        <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                        <input type="password" name="password" class="form-control mb-2" placeholder="Mot de passe"
                            required>
                        <button type="submit" class="btn btn-success w-100">S'inscrire</button>
                    </form>
                    <div id="registerMessage" class="mt-2 text-danger"></div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (!empty($_SESSION['user'])): ?>
    <!-- Modal Déconnexion -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Déconnexion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <p>Voulez-vous vraiment vous déconnecter ?</p>
                    <button id="confirmLogoutBtn" class="btn btn-danger w-100">Se déconnecter</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
