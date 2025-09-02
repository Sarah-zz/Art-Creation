<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fonction de sécurité pour afficher du texte HTML safe
function safe(string $value = ''): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$user = $_SESSION['user'] ?? null;
?>

<div class="container mt-5">
    <h1 class="mb-4">Voici vos informations</h1>

    <?php if ($user): ?>
        <div class="card p-3 shadow-sm">
            <p><strong>Pseudo :</strong> <?= safe($user['pseudo'] ?? '') ?></p>
            <p><strong>Prénom :</strong> <?= safe($user['firstname'] ?? '') ?></p>
            <p><strong>Nom :</strong> <?= safe($user['lastname'] ?? '') ?></p>
            <p><strong>Email :</strong> <?= safe($user['email'] ?? '') ?></p>
        </div>

        <div class="mt-4">
            <h4>Vos favoris</h4>
            <p><em>(wip : liste des tableaux mis en favoris)</em></p>

            <h4 class="mt-3">Vos ateliers</h4>
            <p><em>(wip : inscriptions + nombre de participants)</em></p>
        </div>
    <?php else: ?>
        <p>Vous devez être connecté pour voir vos informations.</p>
    <?php endif; ?>
</div>