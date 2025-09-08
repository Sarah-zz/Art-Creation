<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<li class="nav-item dropdown d-flex align-items-center">
    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button"
        data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa-solid fa-user-circle fa-lg <?php echo !empty($_SESSION['user']) ? 'icon-logged' : ''; ?>"></i>
    </a>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
        <?php if (!empty($_SESSION['user'])): ?>
            <li><a class="dropdown-item" href="/profil">Mon profil</a></li>
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">Se déconnecter</a>
            </li>
        <?php else: ?>
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Se connecter</a></li>
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#registerModal">S'inscrire</a></li>
        <?php endif; ?>
    </ul>
</li>