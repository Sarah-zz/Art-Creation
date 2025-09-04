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
    http_response_code(500);
    die(json_encode([
        'success' => false,
        'errors' => ['Erreur MongoDB : ' . $e->getMessage()]
    ]));
}

// --- Routeur ---
$basePath = ''; // vide car site accessible via localhost
$requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

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
        'controller' => __DIR__ . '/../src/Controller/UserController.php',
        'method' => 'profil',
        'view' => null // vue choisie dynamiquement
    ],
    // --- Routes JSON ---
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
    'track-click' => [
        'controller' => __DIR__ . '/../src/Controller/GalleryController.php',
        'method' => 'trackClick',
        'view' => null,
        'json' => true //clics Mongo pour les stats admin
    ],
    'toggle-favorite' => [
        'controller' => __DIR__ . '/../src/Controller/GalleryController.php',
        'method' => 'toggleFavorite',
        'view' => null,
        'json' => true
    ],


];

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

        // --- Routes JSON ---
        if (!empty($matchedRoute['json'])) {
            // Nettoyer tout ce qui traîne dans le buffer (warnings, espaces…)
            if (ob_get_length()) {
                ob_clean();
            }

            header('Content-Type: application/json; charset=utf-8');

            // ⚠️ Important : passer $_POST ou [] par défaut
            $result = $controller->$method($_POST ?? []);

            // Encodage JSON propre
            echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit;
        }

        // --- Routes classiques (pages HTML) ---
        if ($controllerClass === "\\App\\Controller\\PageController") {
            $data = $controller->$method($requestUri);
        } else {
            $data = $controller->$method();
        }

        // --- Vue dynamique si route view null ---
        if ($matchedRoute['view'] === null && !empty($data['view'])) {
            $viewToInclude = $data['view'];
        } else {
            $viewToInclude = $matchedRoute['view'] ?? $viewToInclude;
        }

    } else {
        http_response_code(500);
        echo "Erreur interne : contrôleur introuvable.";
        exit();
    }
} else {
    http_response_code(404);
}

// --- Extraction des données pour la vue ---
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
