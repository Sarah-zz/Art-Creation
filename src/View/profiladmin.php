<?php
// Sécurité : s'assurer que l'admin est connecté
if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
    header('Location: /');
    exit;
}

// Données passées depuis le controller
$images = $images ?? [];
$workshops = $workshops ?? [];
?>

<h1>Dashboard Lya</h1>

<!-- GALERIE -->

<h2 class="mt-4">Gestion de la galerie</h2>
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

<!-- ATELIERS -->
<h2 class="mt-5">Gestion des ateliers</h2>
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
            <th>Places disponibles</th>
            <th>Places réservées</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($workshops)): ?>
            <?php foreach ($workshops as $w): ?>
                <tr>
                    <td><?= htmlspecialchars($w['id']) ?></td>
                    <td><?= htmlspecialchars($w['name']) ?></td>
                    <td><?= htmlspecialchars($w['date']) ?></td>
                    <td><?= htmlspecialchars($w['level'] ?? '') ?></td>
                    <td><?= (int) ($w['max_places'] ?? 0) - (int) ($w['registered'] ?? 0) ?></td>
                    <td><?= (int) ($w['registered'] ?? 0) ?></td>
                    <td>
                        <a href="/admin/workshops/edit/<?= $w['id'] ?>" class="btn btn-primary btn-sm">Modifier</a>
                        <a href="/admin/workshops/delete/<?= $w['id'] ?>" class="btn btn-danger btn-sm"
                            onclick="return confirm('Voulez-vous vraiment supprimer cet atelier ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center">Aucun atelier disponible.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>