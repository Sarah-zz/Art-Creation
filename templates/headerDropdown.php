<?php if (session_status() === PHP_SESSION_NONE)
    session_start(); ?>

<li class="nav-item dropdown d-flex align-items-center">
    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button"
        data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa-solid fa-user-circle fa-lg"></i>
    </a>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
        <?php if (!empty($_SESSION['user'])): ?>
            <!-- Utilisateur connecté -->
            <li><a class="dropdown-item" href="/profil">Mon profil</a></li>
            <li><a class="dropdown-item" href="#" id="logoutBtn">Se déconnecter</a></li>
        <?php else: ?>
            <!-- Utilisateur non connecté -->
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Se connecter</a></li>
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#registerModal">S'inscrire</a></li>
        <?php endif; ?>
    </ul>
</li>

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
                        <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
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
                        <input type="text" name="username" class="form-control mb-2" placeholder="Pseudo" required>
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