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
    // Routes JSON
    'login' => ['controller' => __DIR__ . '/../src/Controller/UserController.php', 'method' => 'login', 'json' => true],
    'register' => ['controller' => __DIR__ . '/../src/Controller/UserController.php', 'method' => 'register', 'json' => true],
    'logout' => ['controller' => __DIR__ . '/../src/Controller/UserController.php', 'method' => 'logout', 'json' => true],
];

// Gestion des routes avec ID pour edit/delete
if (preg_match('#^admin/gallery/edit/(\d+)$#', $requestUri, $matches)) $_GET['id'] = $matches[1];
if (preg_match('#^admin/gallery/delete/(\d+)$#', $requestUri, $matches)) $_GET['id'] = $matches[1];
if (preg_match('#^admin/workshops/edit/(\d+)$#', $requestUri, $matches)) $_GET['id'] = $matches[1];
if (preg_match('#^admin/workshops/delete/(\d+)$#', $requestUri, $matches)) $_GET['id'] = $matches[1];

$matchedRoute = $routes[$requestUri] ?? null;
$viewToInclude = __DIR__ . '/../src/View/error404.php';
$data = [];

if ($matchedRoute && file_exists($matchedRoute['controller'])) {
    include $matchedRoute['controller'];
    $controllerClass = "\\App\\Controller\\" . basename($matchedRoute['controller'], '.php');
    $controller = new $controllerClass();
    $method = $matchedRoute['method'] ?? 'index';

    // Routes JSON
    if (!empty($matchedRoute['json'])) {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        try {
            $result = $controller->$method($_POST ?? []);
            echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    // Pages normales
    $data = $controller->$method($_GET['id'] ?? null);

    // Vue dynamique si précisée
    if ($matchedRoute['view'] === null && !empty($data['view'])) {
        $viewToInclude = $data['view'];
    } else {
        $viewToInclude = $matchedRoute['view'] ?? $viewToInclude;
    }
} else {
    http_response_code(404);
}

// Extraction des données pour la vue
if (!empty($data) && is_array($data)) extract($data);

// Inclusion header, vue, footer
include __DIR__ . '/../templates/header.php';
if (file_exists($viewToInclude)) {
    include $viewToInclude;
} else {
    http_response_code(404);
    include __DIR__ . '/../src/View/error404.php';
}
include __DIR__ . '/../templates/footer.php';
ob_end_flush();
