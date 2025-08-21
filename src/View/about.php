<?php
// Variables pour la page
$pageTitle = "Bienvenue dans mon monde : Art & Création";
$subtitle = "Qui suis-je ?";
$imageSrc = "/assets/img/atelier-closeup-lya.jpg";
$imageAlt = "Atelier de Lya, peintures";
$buttonLabel = "Découvrir mon travail";
$buttonLink = "/galerie";
?>

<div class="container py-5">
    <h1 class="text-center mb-4"><?= htmlspecialchars($pageTitle) ?></h1>

    <div class="row align-items-center">
        <div class="col-md-6 mb-4 mb-md-0">
            <img src="<?= htmlspecialchars($imageSrc) ?>" class="img-fluid rounded shadow"
                alt="<?= htmlspecialchars($imageAlt) ?>">
        </div>
        <div class="col-md-6">
            <h2><?= htmlspecialchars($subtitle) ?></h2>

            <p>Je m'appelle Lya, et je suis artiste peintre. J'ai grandi en Afrique, entourée de
                couleurs vives, de textures riches et de rythmes qui semblent danser dans chaque paysage. Ces souvenirs
                de mon enfance – les marchés animés, les tissus chatoyants, les motifs traditionnels – nourrissent
                encore aujourd'hui mon univers artistique.</p>

            <p>Mon travail explore la couleur, la matière et les émotions. Chaque toile est une rencontre entre mes
                expériences, mon imaginaire et les influences de l’art africain qui m’ont tant inspirée. J’aime que mes
                œuvres racontent une histoire, suscitent une émotion et invitent chacun à un voyage unique.
            </p>

            <p>Afin de partager ma passion, je propose des ateliers de peinture pour adultes et enfants. J’aime
                partager mon savoir-faire et voir chacun exprimer sa créativité librement, en laissant les couleurs et
                les formes guider leur imagination.</p>

            <a href="<?= htmlspecialchars($buttonLink) ?>" class="btn btn-footer mt-3">
                <?= htmlspecialchars($buttonLabel) ?>
            </a>
        </div>
    </div>
</div>