<?php
// Vérifie si on est en modification
$isEdit = isset($workshop);
$name = $isEdit ? $workshop->getName() : '';
$date = $isEdit ? $workshop->getDate()->format('Y-m-d\TH:i') : '';
$level = $isEdit ? $workshop->getLevel() : '';
$maxPlaces = $isEdit ? $workshop->getMaxPlaces() : '';
$description = $isEdit ? $workshop->getDescription() ?? '' : '';
$action = $isEdit ? "/admin/workshops/edit/{$workshop->getId()}" : "/admin/workshops/add";
?>


<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0"><?= $isEdit ? "Modifier l'atelier" : "Ajouter un nouvel atelier" ?></h4>
                </div>
                <div class="card-body">
                    <form method="post" action="<?= $action ?>">
                        <div class="admintab">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom de l'atelier</label>
                                <input type="text" id="name" name="name" class="form-control"
                                    value="<?= htmlspecialchars($name) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="date" class="form-label">Date et heure</label>
                                <input type="datetime-local" id="date" name="date" class="form-control"
                                    value="<?= htmlspecialchars($date) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="level" class="form-label">Niveau</label>
                                <input type="text" id="level" name="level" class="form-control"
                                    value="<?= htmlspecialchars($level) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="max_places" class="form-label">Nombre maximum de participants</label>
                                <input type="number" id="max_places" name="max_places" class="form-control"
                                    value="<?= htmlspecialchars($maxPlaces) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea id="description" name="description" class="form-control"
                                    rows="3"><?= htmlspecialchars($description) ?></textarea>
                            </div>
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