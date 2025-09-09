<?php
// Vérifie si on est en modification
$isEdit = isset($image);
$title = $isEdit ? $image['title'] : '';
$imageUrl = $isEdit ? $image['image'] : '';
$description = $isEdit ? $image['description'] : '';
$size = $isEdit ? $image['size'] : '';
$action = $isEdit ? "/admin/gallery/edit/{$image['id']}" : "/admin/gallery/add";
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><?= $isEdit ? "Modifier l'image" : "Ajouter une nouvelle image" ?></h4>
                </div>
                <div class="card-body">
                    <form method="post" action="<?= $action ?>">

                        <div class="mb-3">
                            <label for="title" class="form-label">Titre</label>
                            <input type="text" id="title" name="title" class="form-control"
                                value="<?= htmlspecialchars($title) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">URL de l'image</label>
                            <input type="text" id="image" name="image" class="form-control"
                                value="<?= htmlspecialchars($imageUrl) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" class="form-control"
                                rows="3"><?= htmlspecialchars($description) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="size" class="form-label">Taille</label>
                            <input type="text" id="size" name="size" class="form-control"
                                value="<?= htmlspecialchars($size) ?>">
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit"
                                class="btn btn-primary"><?= $isEdit ? "Modifier" : "Ajouter" ?></button>
                            <a href="/admin" class="btn btn-secondary">Annuler</a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>