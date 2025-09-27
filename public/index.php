<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoloader Composer
require_once __DIR__ . '/../vendor/autoload.php';

// --- Chargement des variables d'environnement ---
$dotenvPath = __DIR__ . '/../.env';
if (file_exists($dotenvPath)) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
} else {
    // Production Platform.sh
    $_ENV['APP_ENV'] = getenv('APP_ENV') ?: 'production';
    $_ENV['APP_DEBUG'] = getenv('APP_DEBUG') ?: false;
    $_ENV['DATABASE_URL'] = getenv('DATABASE_URL') ?: null;
}

// --- Connexion MySQL automatique Platform.sh / local ---
$dbHost = $dbPort = $dbName = $dbUser = $dbPass = null;

if (getenv('PLATFORM_RELATIONSHIPS')) {
    $relationships = json_decode(base64_decode(getenv('PLATFORM_RELATIONSHIPS')), true);
    if (isset($relationships['database'][0])) {
        $db = $relationships['database'][0];
        $dbHost = $db['host'];
        $dbPort = $db['port'];
        $dbName = ltrim($db['path'], '/');
        $dbUser = $db['username'];
        $dbPass = $db['password'];
    }
} elseif (isset($_ENV['DATABASE_URL'])) {
    $parts = parse_url($_ENV['DATABASE_URL']);
    $dbHost = $parts['host'] ?? '127.0.0.1';
    $dbPort = $parts['port'] ?? 3306;
    $dbName = ltrim($parts['path'], '/');
    $dbUser = $parts['user'] ?? 'root';
    $dbPass = $parts['pass'] ?? '';
}

try {
    if ($dbHost && $dbName) {
        $pdo = new PDO(
            "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4",
            $dbUser,
            $dbPass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
    }
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// --- Récupération de l'URL demandée ---
$requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// --- Définition des routes ---
$routes = [
    '' => ['controller' => __DIR__ . '/../src/Controller/HomeController.php', 'method' => 'render', 'view' => __DIR__ . '/../src/View/home.php'],
    'galerie' => ['controller' => __DIR__ . '/../src/Controller/GalleryController.php', 'method' => 'index', 'view' => __DIR__ . '/../src/View/gallery.php'],
    'contact' => ['controller' => __DIR__ . '/../src/Controller/ContactController.php', 'method' => 'index', 'view' => __DIR__ . '/../src/View/contact.php'],
    'a-propos' => ['controller' => __DIR__ . '/../src/Controller/PageController.php', 'method' => 'render', 'view' => __DIR__ . '/../src/View/about.php'],
    'ateliers' => ['controller' => __DIR__ . '/../src/Controller/WorkshopsController.php', 'method' => 'index', 'view' => __DIR__ . '/../src/View/workshops.php'],

    // Routes JSON / API
    'login' => ['controller' => __DIR__ . '/../src/Controller/UserController.php', 'method' => 'login', 'json' => true],
    'register' => ['controller' => __DIR__ . '/../src/Controller/UserController.php', 'method' => 'register', 'json' => true],
    'logout' => ['controller' => __DIR__ . '/../src/Controller/UserController.php', 'method' => 'logout', 'json' => true],
    'track-click' => ['controller' => __DIR__ . '/../src/Controller/GalleryController.php', 'method' => 'trackClick', 'json' => true],
    'toggle-favorite' => ['controller' => __DIR__ . '/../src/Controller/GalleryController.php', 'method' => 'toggleFavorite', 'json' => true],

    // Ateliers inscription
    'workshops/register' => ['controller' => __DIR__ . '/../src/Controller/WorkshopsController.php', 'method' => 'register', 'json' => true],

    // Admin - Galerie
    'admin/gallery/add' => ['controller' => __DIR__ . '/../src/Controller/AdminController.php', 'method' => 'addGallery', 'view' => null],
    'admin/gallery/edit' => ['controller' => __DIR__ . '/../src/Controller/AdminController.php', 'method' => 'editGallery', 'view' => null],
    'admin/gallery/delete' => ['controller' => __DIR__ . '/../src/Controller/AdminController.php', 'method' => 'deleteGallery', 'view' => null],

    // Admin - Ateliers
    'admin/workshops' => ['controller' => __DIR__ . '/../src/Controller/AdminController.php', 'method' => 'adminWorkshops', 'view' => null],
    'admin/workshops/add' => ['controller' => __DIR__ . '/../src/Controller/AdminController.php', 'method' => 'addWorkshop', 'view' => null],
    'admin/workshops/edit' => ['controller' => __DIR__ . '/../src/Controller/AdminController.php', 'method' => 'editWorkshop', 'view' => null],
    'admin/workshops/delete' => ['controller' => __DIR__ . '/../src/Controller/AdminController.php', 'method' => 'deleteWorkshop', 'view' => null],

    // Profil utilisateur
    'profil' => ['controller' => __DIR__ . '/../src/Controller/UserController.php', 'method' => 'profil', 'view' => null],
    'admin' => ['controller' => __DIR__ . '/../src/Controller/AdminController.php', 'method' => 'dashboard', 'view' => null],
];

// --- Gestion des routes avec ID pour edit/delete ---
foreach (['admin/gallery/edit', 'admin/gallery/delete', 'admin/workshops/edit', 'admin/workshops/delete'] as $pattern) {
    if (preg_match("#^$pattern/(\d+)$#", $requestUri, $matches))
        $_GET['id'] = $matches[1];
}

// --- Résolution de la route ---
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

// --- Inclusion des templates et extraction des données ---
if (!empty($data) && is_array($data)) {
    extract($data);
}

include __DIR__ . '/../templates/header.php';
include file_exists($viewToInclude) ? $viewToInclude : __DIR__ . '/../src/View/error404.php';
include __DIR__ . '/../templates/footer.php';
ob_end_flush();
