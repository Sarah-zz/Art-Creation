<?php
// Affichage complet des erreurs PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Test de connexion à MySQL
$mysqli = new mysqli(
    getenv('MYSQL_HOST'), 
    getenv('DB_USER'), 
    getenv('DB_PASSWORD'), 
    getenv('MYSQL_DATABASE')
);

if ($mysqli->connect_error) {
    die("Erreur MySQL : " . $mysqli->connect_error);
}

// Connexion réussie
echo "<h2>Connexion MySQL OK !</h2>";

// Test d’un simple query
$result = $mysqli->query("SELECT NOW() AS current_time");
if ($result) {
    $row = $result->fetch_assoc();
    echo "Heure actuelle depuis MySQL : " . $row['current_time'];
} else {
    echo "Erreur lors de la requête MySQL : " . $mysqli->error;
}

// Note : MongoDB désactivé temporairement
echo "<p>MongoDB désactivé pour le debug</p>";
