<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoloader Composer et variables d'environnement
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Récupération de l'URL demandée
$requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Définition des routes
$routes = [
    '' => ['controller' => __DIR__ . '/../src/Controller/HomeController.php', 'method' => 'render', 'view' => __DIR__ . '/../src/View/home.php'],
    'galerie' => ['controller' => __DIR__ . '/../src/Controller/GalleryController.php', 'method' => 'index', 'view' => __DIR__ . '/../src/View/gallery.php'],
    'contact' => ['controller' => __DIR__ . '/../src/Controller/ContactController.php', 'method' => 'index', 'view' => __DIR__ . '/../src/View/contact.php'],
    'a-propos' => ['controller' => __DIR__ . '/../src/Controller/PageController.php', 'method' => 'render', 'view' => __DIR__ . '/../src/View/about.php'],
    'ateliers' => ['controller' => __DIR__ . '/../src/Controller/WorkshopsController.php', 'method' => 'index', 'view' => __DIR__ . '/../src/View/workshops.php'],
];

// Gestion des routes avec ID pour edit/delete
foreach (['admin/gallery/edit', 'admin/gallery/delete', 'admin/workshops/edit', 'admin/workshops/delete'] as $pattern) {
    if (preg_match("#^$pattern/(\d+)$#", $requestUri, $matches))
        $_GET['id'] = $matches[1];
}

$matchedRoute = $routes[$requestUri] ?? null;
$viewToInclude = __DIR__ . '/../src/View/error404.php';
$data = [];

if ($matchedRoute && file_exists($matchedRoute['controller'])) {
    include $matchedRoute['controller'];
    $controllerClass = "\\App\\Controller\\" . basename($matchedRoute['controller'], '.php');
    $controller = new $controllerClass();
    $method = $matchedRoute['method'] ?? 'index';
    $data = $controller->$method($_GET['id'] ?? null);
    $viewToInclude = $matchedRoute['view'] ?? $viewToInclude;
} else {
    http_response_code(404);
}

if (!empty($data) && is_array($data))
    extract($data);

include __DIR__ . '/../templates/header.php';
include file_exists($viewToInclude) ? $viewToInclude : __DIR__ . '/../src/View/error404.php';
include __DIR__ . '/../templates/footer.php';
ob_end_flush();
