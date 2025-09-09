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
$userWorkshops = $userWorkshops ?? []; // Evite les warnings
?>

<div class="container mt-5">
    <h1 class="mb-4">Hello <strong><?= safe($user['pseudo'] ?? '') ?></strong> !</h1>

    <?php if ($user): ?>
        <div class="card p-3 shadow-sm mb-4">
            <p><strong>Pseudo :</strong> <?= safe($user['pseudo'] ?? '') ?></p>
            <p><strong>Prénom :</strong> <?= safe($user['firstname'] ?? '') ?></p>
            <p><strong>Nom :</strong> <?= safe($user['lastname'] ?? '') ?></p>
            <p><strong>Email :</strong> <?= safe($user['email'] ?? '') ?></p>
        </div>

        <div class="mt-4">
            <h4>Vos favoris</h4>
            <p><em>(wip : liste des tableaux mis en favoris)</em></p>

            <h4 class="mt-3">Vos ateliers</h4>
            <?php if (!empty($userWorkshops)): ?>
                <div class="row">
                    <?php foreach ($userWorkshops as $workshop): ?>
                        <div class="col-12 col-md-4 mb-3">
                            <div class="workshop-card p-2 border rounded h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <p><strong><?= safe($workshop['name'] ?? '') ?></strong></p>
                                    <p><?= safe($workshop['date'] ?? '') ?> Durée : 3h</p>
                                    <p id="spots-<?= (int) ($workshop['workshop_id'] ?? 0) ?>">
                                        Nombre de participants réservés : <?= (int) ($workshop['participants'] ?? 0) ?>
                                    </p>
                                </div>
                                <button class="btn btn-outline-danger btn-cancel mt-2"
                                    data-workshop-id="<?= (int) ($workshop['workshop_id'] ?? 0) ?>">
                                    Annuler ma réservation
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Vous n’avez aucune inscription pour le moment.</p>
            <?php endif; ?>

        </div>
    <?php else: ?>
        <p>Vous devez être connecté pour voir vos informations.</p>
    <?php endif; ?>
</div>