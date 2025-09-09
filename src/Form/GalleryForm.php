<?php
// Détermine si on est en modification ou ajout
$isEdit = isset($image);
$title = $isEdit ? $image['title'] : '';
$imageUrl = $isEdit ? $image['image'] : '';
$description = $isEdit ? $image['description'] : '';
$size = $isEdit ? $image['size'] : '';
$action = $isEdit ? "/admin/gallery/edit/{$image['id']}" : "/admin/gallery/add";
?>

<h2><?= $isEdit ? "Modifier l'image" : "Ajouter une nouvelle image" ?></h2>

<form method="post" action="<?= $action ?>">
    <div>
        <label>Titre</label>
        <input type="text" name="title" value="<?= htmlspecialchars($title) ?>" required>
    </div>
    <div>
        <label>URL de l'image</label>
        <input type="text" name="image" value="<?= htmlspecialchars($imageUrl) ?>" required>
    </div>
    <div>
        <label>Description</label>
        <textarea name="description"><?= htmlspecialchars($description) ?></textarea>
    </div>
    <div>
        <label>Taille</label>
        <input type="text" name="size" value="<?= htmlspecialchars($size) ?>">
    </div>
    <div>
        <button type="submit"><?= $isEdit ? "Modifier" : "Ajouter" ?></button>
    </div>
</form>