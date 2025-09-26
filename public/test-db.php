<?php
$mysqli = new mysqli(getenv('MYSQL_HOST'), getenv('DB_USER'), getenv('DB_PASSWORD'), getenv('MYSQL_DATABASE'));

if ($mysqli->connect_error) {
    die("Erreur MySQL: " . $mysqli->connect_error);
}

echo "Connexion MySQL OK";
