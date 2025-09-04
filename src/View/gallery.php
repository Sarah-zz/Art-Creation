<?php
$images = $data['images'] ?? [];
$isLogged = !empty($_SESSION['user']['id'] ?? null);
?>

<div class="container mt-4 gallery-space">
    <h1 class="text-center mb-5">Galerie</h1>

    <div class="row g-4">
        <?php if (!empty($images)): ?>
            <?php foreach ($images as $img): ?>
                <div class="col-md-4 d-flex">
                    <div class="card gallery-card img-clickable h-100 w-100" data-id="<?= (int) $img['id'] ?>"
                        data-title="<?= htmlspecialchars($img['title']) ?>"
                        data-size="<?= htmlspecialchars($img['size'] ?? '') ?>"
                        data-description="<?= htmlspecialchars($img['description'] ?? '') ?>"
                        data-logged="<?= $isLogged ? '1' : '0' ?>">

                        <div class="gallery-img-wrapper">
                            <img src="<?= htmlspecialchars($img['image']) ?>" class="gallery-img"
                                alt="<?= htmlspecialchars($img['title']) ?>">
                        </div>

                        <div class="card-body text-center position-relative">
                            <h5 class="card-title mb-0"><?= htmlspecialchars($img['title']) ?></h5>

                            <?php if ($isLogged): ?>
                                <span class="heart-icon" data-image-id="<?= (int) $img['id'] ?>">♥</span>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-muted">Aucune image disponible pour le moment.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Modal -->
<div id="imageModal" class="modal-overlay">
    <div class="modal-content">
        <button type="button" class="close">&times;</button>
        <img id="modalImage" class="img-fluid mb-3">
        <h3 id="modalTitle"></h3>
        <p id="modalSize" class="text-muted small"></p>
        <p id="modalDescription"></p>
    </div>
</div>