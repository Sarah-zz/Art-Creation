<?php

namespace App\Database;

use MongoDB\Client;

class MongoDbConnection
{
    private static ?Client $client = null;
    private static string $databaseName;

    // Initialise la connexion MongoDB
    public static function initialize(?string $uri = null, ?string $dbName = null): void
    {
        try {
            // Détection Platform.sh
            if (getenv('PLATFORM_RELATIONSHIPS')) {
                $relationships = json_decode(getenv('PLATFORM_RELATIONSHIPS'), true);
                $mongo = $relationships['mongodb'][0];

                $uri = "mongodb://{$mongo['username']}:{$mongo['password']}@{$mongo['host']}:{$mongo['port']}/{$mongo['path']}";
                $dbName = $mongo['path'];
            }

            // Fallback pour dev local
            if ($uri === null || $dbName === null) {
                $uri = $uri ?? $_ENV['MONGO_URI'] ?? 'mongodb://root:password@localhost:27017/ma_base';
                $dbName = $dbName ?? $_ENV['MONGO_DB'] ?? 'ma_base';
            }

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
