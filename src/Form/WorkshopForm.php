<?php
$isEdit = isset($workshop);

$name = $isEdit ? $workshop->getName() : '';
$level = $isEdit ? $workshop->getLevel() : '';
$maxPlaces = $isEdit ? $workshop->getMaxPlaces() : '';
$description = $isEdit ? $workshop->getDescription() ?? '' : '';

// Gestion de la date
if ($isEdit) {
    $date = $workshop->getDate()->format('Y-m-d');
    $hour = $workshop->getDate()->format('H');
} else {
    // Si ajout "prochain samedi sur 2"
    if (!empty($_GET['nextSaturday'])) {
        $now = new DateTimeImmutable();
        $nextSaturday = $now->modify('next saturday');
        // Optionnel : sauter une semaine sur 2
        if ((int) $nextSaturday->format('W') % 2 === 0) {
            $nextSaturday = $nextSaturday->modify('+1 week');
        }
        $date = $nextSaturday->format('Y-m-d');
        $hour = '10'; // heure par défaut
    } else {
        $date = '';
        $hour = '';
    }
}

$action = $isEdit ? "/admin/workshops/edit/{$workshop->getId()}" : "/admin/workshops/add";

// Heures disponibles
$hours = range(10, 17);
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

                        <div class="mb-3">
                            <label for="name" class="form-label">Nom de l'atelier</label>
                            <input type="text" id="name" name="name" class="form-control"
                                value="<?= htmlspecialchars($name) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" id="date" name="date" class="form-control"
                                value="<?= htmlspecialchars($date) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="hour" class="form-label">Heure</label>
                            <select id="hour" name="hour" class="form-select" required>
                                <option value="">Sélectionnez une heure</option>
                                <?php foreach ($hours as $h): ?>
                                    <option value="<?= $h ?>" <?= ($hour == $h) ? 'selected' : '' ?>>
                                        <?= $h ?>h00
                                    </option>
                                <?php endforeach; ?>
                            </select>
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