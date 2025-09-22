<?php
// Sécurité : s'assurer que l'admin est connecté
if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
    header('Location: /');
    exit;
}

// Données passées depuis le controller
$images = $images ?? [];
$workshops = $workshops ?? [];
// Assurer que chaque atelier a une clé 'isPast'
foreach ($workshops as &$w) {
    if (!isset($w['isPast'])) {
        $w['isPast'] = false;
    }
}
unset($w);
$topClics = $topClics ?? [];
?>

<h1>Dashboard Lya</h1><br>

<!-- Onglets Bootstrap -->
<ul class="nav nav-tabs" id="adminTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="gallery-tab" data-bs-toggle="tab" data-bs-target="#gallery" type="button"
            role="tab">Galerie</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="workshops-tab" data-bs-toggle="tab" data-bs-target="#workshops" type="button"
            role="tab">Ateliers</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats" type="button"
            role="tab">Statistiques</button>
    </li>
</ul>

<div class="tab-content mt-3" id="adminTabsContent">

    <!-- Onglet Galerie -->

    <div class="tab-pane fade show active" id="gallery" role="tabpanel">
        <p>Gestion des modifications de la page galerie. Ajouter - Modifier - Supprimer</p>
        <div class="mb-3">
            <a href="/admin/gallery/add" class="btn btn-success">Ajouter un nouveau tableau</a>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Taille</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($images)): ?>
                    <?php foreach ($images as $img): ?>
                        <tr>
                            <td><?= htmlspecialchars($img['id']) ?></td>
                            <td><img src="<?= htmlspecialchars($img['image']) ?>" alt="<?= htmlspecialchars($img['title']) ?>"
                                    width="100"></td>
                            <td><?= htmlspecialchars($img['title']) ?></td>
                            <td><?= htmlspecialchars($img['description'] ?? '') ?></td>
                            <td><?= htmlspecialchars($img['size'] ?? '') ?></td>
                            <td>
                                <a href="/admin/gallery/edit/<?= $img['id'] ?>" class="btn btn-primary btn-sm">Modifier</a>
                                <a href="/admin/gallery/delete/<?= $img['id'] ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Voulez-vous vraiment supprimer ce tableau ?');">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Aucune image disponible.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Onglet Ateliers -->
    <div class="tab-pane fade" id="workshops" role="tabpanel">
        <p>Gestion des ateliers. Ajouter - Modifier - Supprimer</p>
        <div class="mb-3">
            <a href="/admin/workshops/add" class="btn btn-success">Ajouter un nouvel atelier</a>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Date</th>
                    <th>Niveau</th>
                    <th>Décompte places</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($workshops)): ?>
                    <?php foreach ($workshops as $w): ?>
                        <tr class="<?= $w['isPast'] ? '' : '' ?>">
                            <td><?= htmlspecialchars($w['id']) ?></td>
                            <td><?= htmlspecialchars($w['name']) ?>         <?= $w['isPast'] ? '(atelier terminé)' : '' ?></td>
                            <td><?= htmlspecialchars($w['date']) ?></td>
                            <td><?= htmlspecialchars($w['level']) ?></td>
                            <td><?= htmlspecialchars($w['registered']) ?>/<?= htmlspecialchars($w['max_places']) ?></td>
                            <td>
                                <?php if (!$w['isPast']): ?>
                                    <a href="/admin/workshops/edit/<?= $w['id'] ?>" class="btn btn-primary btn-sm">Modifier</a>
                                <?php else: ?>
                                    <span class="text-secondary fw-bold me-2">Atelier passé</span>
                                <?php endif; ?>
                                <a href="/admin/workshops/delete/<?= $w['id'] ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Voulez-vous vraiment supprimer cet atelier ?');">Supprimer</a>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Aucun atelier disponible.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>




    <!-- Onglet Statistiques -->
    <div class="tab-pane fade" id="stats" role="tabpanel">
        <p>Ici, un récapitulatif des tableaux sur deux critères. Le premier, tableaux les plus cliqués par les
            internautes. Le deuxième, classement des tableaux mis en favoris.</p>
        <!-- CLICS -->
        <h3>Tableaux les plus cliqués par les internautes</h3>
        <?php if (!empty($topClics)):
            $maxClics = max(array_column($topClics, 'total_clics'));
            ?>
            <div class="list-group mt-3">
                <?php foreach ($topClics as $clic):
                    $title = $clic['tableau_title'] ?? 'Titre inconnu';
                    $totalClics = $clic['total_clics'] ?? 0;
                    $widthPercent = $maxClics > 0 ? ($totalClics / $maxClics) * 100 : 0;
                    ?>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span><?= htmlspecialchars($clic['tableau_title']) ?></span>
                            <span class="badge bg-warning"><?= $totalClics ?> clic<?= $totalClics > 1 ? 's' : '' ?></span>
                        </div>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $widthPercent ?>%;"
                                aria-valuenow="<?= $totalClics ?>" aria-valuemin="0" aria-valuemax="<?= $maxClics ?>"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucun clic enregistré pour le moment.</p>
        <?php endif; ?><br>

        <!-- FAVORIS -->
        <h3>Top des tableaux mis en favoris</h3>
        <?php if (!empty($topFavorites)): ?>
            <div class="list-group mt-2">
                <?php foreach ($topFavorites as $fav):
                    $title = $fav['title'] ?? 'Titre inconnu';
                    $totalFavs = $fav['total_favs'] ?? 0;
                    $widthPercent = $maxClics > 0 ? ($totalFavs / $maxClics) * 100 : 0;
                    ?>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span><?= htmlspecialchars($title) ?></span>
                            <span class="badge bg-danger"><?= $totalFavs ?> favori<?= $totalFavs > 1 ? 's' : '' ?></span>
                        </div>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: <?= $widthPercent ?>%;"
                                aria-valuenow="<?= $totalFavs ?>" aria-valuemin="0" aria-valuemax="<?= $maxFavs ?>">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucun favori enregistré pour le moment.</p>
        <?php endif; ?>
        <br>
        <br>
    </div>

</div>