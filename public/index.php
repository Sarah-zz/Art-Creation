<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- Autoloader Composer et variables d'environnement ---
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

use App\Database\MongoDbConnection;

// --- Initialisation MongoDB ---
try {
    MongoDbConnection::initialize($_ENV['MONGO_URI'], $_ENV['MONGO_APP_DB']);
} catch (\Exception $e) {
    die("Erreur MongoDB : " . $e->getMessage());
}

// --- Routeur ---
$basePath = 'ArtCreation';
$base_url = !empty($basePath) ? '/' . trim($basePath, '/') : '';

$requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
if (!empty($basePath) && strpos($requestUri, trim($basePath, '/')) === 0) {
    $requestUri = substr($requestUri, strlen(trim($basePath, '/')));
    $requestUri = trim($requestUri, '/');
}

// --- Définition des routes ---
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
        'controller' => __DIR__ . '/../src/Controller/WorkshopsController.php',
        'method' => 'index',
        'view' => __DIR__ . '/../src/View/workshops.php'
    ],
    'mentionslegales' => [
        'controller' => __DIR__ . '/../src/Controller/PageController.php',
        'method' => 'render',
        'view' => __DIR__ . '/../src/View/mentionslegales.php'
    ],
    'profil' => [
    'controller' => __DIR__ . '/../src/Controller/PageController.php',
    'method' => 'render',
    'view' => __DIR__ . '/../src/View/profil.php'
],
    // --- Routes JSON pour UserController ---
    'register' => [
        'controller' => __DIR__ . '/../src/Controller/UserController.php',
        'method' => 'register',
        'view' => null,
        'json' => true
    ],
    'login' => [
        'controller' => __DIR__ . '/../src/Controller/UserController.php',
        'method' => 'login',
        'view' => null,
        'json' => true
    ],
    'logout' => [
        'controller' => __DIR__ . '/../src/Controller/UserController.php',
        'method' => 'logout',
        'view' => null,
        'json' => true
    ],
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

        // --- Gestion des routes JSON ---
        if (!empty($matchedRoute['json']) && $matchedRoute['json'] === true) {
            header('Content-Type: application/json');
            $result = $controller->$method($_POST ?? []);
            echo json_encode($result);
            exit;
        }

        // --- Gestion des routes classiques ---
        if ($controllerClass === "\\App\\Controller\\PageController") {
            $data = $controller->$method($requestUri);
        } else {
            $data = $controller->$method();
        }

        $viewToInclude = $matchedRoute['view'] ?? $viewToInclude;
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
