<?php

namespace App\Database;

use MongoDB\Client;

class MongoDbConnection
{
    private static ?Client $client = null;
    private static string $databaseName;

    // Initialise la connexion MongoDB avec URI et nom de base
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

    // Retourne le client MongoDB
    public static function getClient(): Client
    {
        if (self::$client === null) {
            throw new \Exception("Connexion MongoDB non initialisée. Appelez initialize() d'abord.");
        }
        return self::$client;
    }

    // Retourne la base de données MongoDB
    public static function getDatabase(): \MongoDB\Database
    {
        return self::getClient()->selectDatabase(self::$databaseName);
    }
}
