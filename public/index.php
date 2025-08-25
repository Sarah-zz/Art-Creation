<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Autoloader Composer
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// --- Début routeur ---
$basePath = 'ArtCreation';
$base_url = !empty($basePath) ? '/' . trim($basePath, '/') : '';

$requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
if (!empty($basePath) && strpos($requestUri, trim($basePath, '/')) === 0) {
    $requestUri = substr($requestUri, strlen(trim($basePath, '/')));
    $requestUri = trim($requestUri, '/');
}

// --- Définition des routes ---
// utilisation de "render" pour les pages statiques, comme la home page et la page about
// utilisation de "index" pour les pages dynamiques (formulaires, récupération de données)

$routes = [
    '' => [
        'controller' => __DIR__ . '/../src/Controller/HomeController.php',
        'method' => 'render',
        'view' => __DIR__ . '/../src/View/home.php'
    ],
    'galerie' => [
        'controller' => __DIR__ . '/../src/Controller/GalleryController.php',
        'method' => 'index',
        'view' => __DIR__ . '/../src/View/gallery.php'
    ],
    'contact' => [
        'controller' => __DIR__ . '/../src/Controller/ContactController.php',
        'method' => 'index',
        'view' => __DIR__ . '/../src/View/contact.php'
    ],
    'a-propos' => [
        'controller' => __DIR__ . '/../src/Controller/PageController.php',
        'method' => 'render',
        'view' => __DIR__ . '/../src/View/about.php'
    ],
    'ateliers' => [
        'method' => 'index',
        'controller' => __DIR__ . '/../src/Controller/WorkshopsController.php',
        'view' => __DIR__ . '/../src/View/workshops.php'
    ],
    'mentionslegales' => [
        'method' => 'render',
        'controller' => __DIR__ . '/../src/Controller/PageController.php',
        'view' => __DIR__ . '/../src/View/mentionslegales.php'
    ]
];

// --- Route correspondante ---
$matchedRoute = $routes[$requestUri] ?? $routes[''] ?? null;
$viewToInclude = __DIR__ . '/../src/View/error404.php';
$data = [];

if ($matchedRoute) {
    if (file_exists($matchedRoute['controller'])) {
        include $matchedRoute['controller'];

        $controllerClass = basename($matchedRoute['controller'], '.php');
        $controllerClass = "\\App\\Controller\\$controllerClass";
        $controller = new $controllerClass();

        $method = $matchedRoute['method'] ?? 'index';

        // Pour PageController, passer le nom de la page
        if ($controllerClass === "\\App\\Controller\\PageController") {
            $data = $controller->$method($requestUri);
        } else {
            $data = $controller->$method();
        }
        $viewToInclude = $matchedRoute['view'];
    } else {
        http_response_code(500);
        echo "Erreur interne : Fichier contrôleur introuvable.";
        exit();
    }
} else {
    http_response_code(404);
}

if (!empty($data) && is_array($data)) {
    extract($data);
}


// --- Inclusion header, vue, footer ---
include __DIR__ . '/../templates/header.php';

if (file_exists($viewToInclude)) {
    include $viewToInclude;
} else {
    http_response_code(404);
    include __DIR__ . '/../src/View/error404.php';
}

include __DIR__ . '/../templates/footer.php';
ob_end_flush();
