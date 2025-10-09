<div class="container py-5">
    <h1 class="text-center mb-4"><?= htmlspecialchars($pageTitle) ?></h1>

    <div class="row align-items-center">
        <div class="col-md-6 mb-4 mb-md-0">
            <img src="<?= htmlspecialchars($imageSrc) ?>" class="img-fluid rounded shadow"
                alt="<?= htmlspecialchars($imageAlt) ?>">
        </div>
        <div class="col-md-6">
            <p>Je suis Lya, artiste peintre.</p>
            <p>Mes œuvres explorent la couleur, la matière et les émotions, invitant chacun à un voyage sensoriel unique.</p>
            <p>Je propose aussi des ateliers de peinture pour adultes et enfants, pour partager ma passion et voir chacun exprimer 
                sa créativité librement.</p>
            <a href="<?= htmlspecialchars($buttonLink) ?>" class="btn btn-footer mt-3">
                <?= htmlspecialchars($buttonLabel) ?>
            </a>
        </div>
    </div>
</div>