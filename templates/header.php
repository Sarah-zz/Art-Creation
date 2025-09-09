<?php
$base_url = '';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArtCreation</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (icônes) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <!-- Style CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="/">Art & Création</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="<?php echo $base_url; ?>/">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo $base_url; ?>/galerie">Galerie</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo $base_url; ?>/ateliers">Ateliers</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo $base_url; ?>/a-propos">À propos</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo $base_url; ?>/contact">Contact</a></li>
                    <!-- Profil, inscription / connexion -->
                    <?php include __DIR__ . '/headerDropdown.php'; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">