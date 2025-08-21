<?php
$images = $data['images'] ?? [];
?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Galerie</h1>
    <div class="row">
        <?php if (!empty($images)): ?>
            <?php foreach ($images as $img): ?>
                <div class="col-md-4 mb-3">
                    <div class="card img-clickable" data-title="<?= htmlspecialchars($img['title']) ?>" data-size="1024x768"
                        data-description="Une brève description de l'image.">

                        <img src="<?= $img['url'] ?>" class="card-img-top" alt="<?= htmlspecialchars($img['title']) ?>">

                        <div class="card-body position-relative text-center">
                            <h5 class="card-title mb-0"><?= htmlspecialchars($img['title']) ?></h5>
                            <span class="heart-icon" data-image-id="<?= htmlspecialchars($img['title']) ?>"
                                style="position:absolute; top:50%; right:10px; transform: translateY(-50%); font-size:32px;">♥</span>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">Aucune image disponible pour le moment.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Modal -->
<div id="imageModal" class="modal-overlay">
    <div class="modal-content">
        <span class="close">&times;</span>
        <img id="modalImage">
        <h3 id="modalTitle"></h3>
        <p id="modalSize"></p>
        <p id="modalDescription"></p>
    </div>
</div>