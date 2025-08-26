<h1>Dates de mes ateliers</h1>
<p>En général, je propose des ateliers un samedi sur deux. Tous niveaux bienvenus, de 3 à 99 ans !</p>

<div id="calendar" class="container my-4">
    <div class="row">
        <?php foreach ($calendarMonths as $monthName => $dates): ?>
            <div class="col-12 col-md d-flex flex-column align-items-center mb-4 position-relative">
                <h4 class="text-center mb-3"><?= $monthName ?></h4>
                <?php foreach ($dates as $date): ?>
                    <div class="d-flex justify-content-center mb-2">
                        <div class="day px-3 py-2 text-center" data-date="<?= $date ?>">
                            <?= $date ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if ($monthName !== array_key_last($calendarMonths)): ?>
                    <div class="month-divider"></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal atelier -->
<div class="modal fade" id="atelierCard" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">
            <div class="modal-header">
                <h5 class="modal-title" id="atelierTitle">Détail de l’atelier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p id="atelierLevel"></p>
                <p id="atelierSpots"></p>
                <p id="atelierHours"></p>
            </div>
            <div class="modal-footer">
                <button id="btnInscrire" class="btn btn-primary">Je participe à l'atelier !</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modale auth si non connecté -->
<div class="modal fade" id="authModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4 text-center">
            <h5 class="modal-title mb-3">Pour vous inscrire, veuillez :</h5>
            <div class="d-flex justify-content-center gap-2">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">Se
                    connecter</button>
                <button class="btn btn-success" data-bs-toggle="modal"
                    data-bs-target="#registerModal">S'inscrire</button>
            </div>
            <div class="mt-3">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Carousel Bootstrap -->
<div id="atelierCarousel" class="carousel slide my-5" data-bs-ride="carousel">
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

<script>
    const isLoggedIn = <?= $isLoggedIn ? 'true' : 'false' ?>;
</script>