<?php
// public/index.php

declare(strict_types=1);

// Debug temporaire
ini_set('display_errors', '1');
error_reporting(E_ALL);

// Charger Composer autoload
require __DIR__ . '/../vendor/autoload.php';

use App\Controller\HomeController;

// Créer une instance du contrôleur
$controller = new HomeController();

// Récupérer les données
$data = $controller->render();

// Exemple d’affichage (tu pourras brancher un moteur de templates ensuite)
echo "<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <title>{$data['pageTitle']}</title>
</head>
<body>
    <h1>{$data['pageTitle']}</h1>
    <h2>{$data['subtitle']}</h2>
    <img src='{$data['imageSrc']}' alt='{$data['imageAlt']}' width='300'>
    <p><a href='{$data['buttonLink']}'>{$data['buttonLabel']}</a></p>
</body>
</html>";
