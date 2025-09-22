<?php

namespace App\Database;

use MongoDB\Client;

class MongoDbConnection
{
    private static ?Client $client = null;
    private static string $databaseName;

    /*
    // ===== Ancien code =====
    // Initialise la connexion MongoDB
    public static function initialize(string $uri, string $dbName): void
    {
        try {
            self::$client = new Client($uri);
            self::$databaseName = $dbName;

            // Test de connexion
            self::$client->listDatabases();
            error_log("MongoDB: Connexion réussie à la base '$dbName'.");
        } catch (\Exception $e) {
            error_log("MongoDB: Erreur de connexion: " . $e->getMessage());
            throw new \Exception("Impossible de se connecter à MongoDB: " . $e->getMessage());
        }
    }
    */

    // ===== Nouveau code =====
    // Initialise la connexion MongoDB à partir des variables d'environnement
    public static function initializeFromEnv(): void
    {
        $uri = getenv('MONGO_URL');         // URI Atlas depuis Render ou .env
        $dbName = getenv('MONGO_APP_DB');   // Nom de la base

        if (!$uri || !$dbName) {
            throw new \Exception("Variables d'environnement MONGO_URL ou MONGO_APP_DB manquantes.");
        }

        try {
            self::$client = new Client($uri);
            self::$databaseName = $dbName;

            // Test de connexion
            self::$client->listDatabases();
            error_log("MongoDB: Connexion réussie à la base '$dbName'.");
        } catch (\Exception $e) {
            error_log("MongoDB: Erreur de connexion: " . $e->getMessage());
            throw new \Exception("Impossible de se connecter à MongoDB: " . $e->getMessage());
        }
    }

    // Retourne le client MongoDB
    public static function getClient(): Client
    {
        if (self::$client === null) {
            throw new \Exception("Connexion MongoDB non initialisée. Appelez initializeFromEnv() d'abord.");
        }
        return self::$client;
    }

    // Retourne la base de données MongoDB
    public static function getDatabase(): \MongoDB\Database
    {
        return self::getClient()->selectDatabase(self::$databaseName);
    }
}
