<?php
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
            // Récupérer les infos de la base depuis Platform.sh
            if (getenv('PLATFORM_RELATIONSHIPS')) {
                $relationships = json_decode(getenv('PLATFORM_RELATIONSHIPS'), true);
                $mysql = $relationships['mysql'][0];

                $dsn = "mysql:host={$mysql['host']};dbname={$mysql['path']}";
                $user = $mysql['username'];
                $password = $mysql['password'];
            } else {
                // Fallback pour dev local ou Docker classique
                $dsn = $_ENV['DB_DSN'] ?? 'mysql:host=localhost;dbname=ma_base';
                $user = $_ENV['DB_USER'] ?? 'root';
                $password = $_ENV['DB_PASSWORD'] ?? '';
            }

            self::$pdo = new PDO($dsn, $user, $password);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            die('Erreur de connexion à la base de données : ' . $e->getMessage());
        } catch (\Exception $e) {
            die('Erreur de configuration de la base de données : ' . $e->getMessage());
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
