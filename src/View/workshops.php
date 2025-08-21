<h1><?= htmlspecialchars($title ?? '') ?></h1>
<p><?= htmlspecialchars($content ?? '') ?></p>

<div id="workshops-list">
    <?php if (!empty($workshops)): ?>
        <ul>
            <?php foreach ($workshops as $w): ?>
                <li>
                    <?= htmlspecialchars($w['name']) ?> - Niveau: <?= htmlspecialchars($w['level']) ?> - Places dispo:
                    <?= htmlspecialchars($w['spots']) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucun atelier disponible pour le moment.</p>
    <?php endif; ?>
</div>

<div id="atelierCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="/assets/img/atelier-decouverte-lya.jpg" class="d-block w-100" alt="Atelier 1">
        </div>
        <div class="carousel-item">
            <img src="/assets/img/atelier-decouverte1-lya.jpg" class="d-block w-100" alt="Atelier 2">
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#atelierCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
        <span class="visually-hidden">Précédent</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#atelierCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
        <span class="visually-hidden">Suivant</span>
    </button>
</div>