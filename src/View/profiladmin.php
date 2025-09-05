<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// images viennent du controller
$images = $data['images'] ?? [];
?>

<h1>Dashboard Lya</h1>

<h2 class="mb-4">Administration de la Galerie</h2>

<div class="mb-3">
    <a href="/admin/gallery/add" class="btn btn-success">Ajouter un nouveau tableau</a>
</div>

<!-- Tableau des images existantes -->
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
                    <td>
                        <img src="<?= htmlspecialchars($img['image']) ?>" alt="<?= htmlspecialchars($img['title']) ?>"
                            width="100">
                    </td>
                    <td><?= htmlspecialchars($img['title']) ?></td>
                    <td><?= htmlspecialchars($img['description'] ?? '') ?></td>
                    <td><?= htmlspecialchars($img['size'] ?? '') ?></td>
                    <td>
                        <!-- Actions CRUD -->
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