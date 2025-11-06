<?php
// Classe Singleton pour gérer la connexion à la base de données en utilisant des variables d'environnement.

namespace App\Database;

use PDO;
use PDOException;

class DbConnection
{
    private static $pdo = null;
    private static $instance = null;

    private function __construct()
    {
        try {
            $dsn = $_ENV['DB_DSN'];
            $user = $_ENV['DB_USER'];
            $password = $_ENV['DB_PASSWORD'];

            self::$pdo = new PDO($dsn, $user, $password);
        } catch (PDOException $e) {
            die('Erreur de connexion à la base de données : ' . $e->getMessage());
        } catch (\Exception $e) {
            die('Erreur de configuration de la base de données : ' . $e->getMessage() . ' (Vérifiez votre fichier .env et son chargement dans public/index.php).');
        }
    }

    public static function getInstance(): DbConnection
    {
        if (self::$instance === null) {
            self::$instance = new DbConnection();
        }
        return self::$instance;
    }

    public static function getPdo(): PDO
    {
        self::getInstance();
        return self::$pdo;
    }
}
