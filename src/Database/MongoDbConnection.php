<?php

namespace App\Database;

use MongoDB\Client;
use MongoDB\Database;
use Exception;

class MongoDbConnection
{
    private static ?Client $client = null;
    private static ?string $databaseName = null;

    /**
     * Initialise la connexion MongoDB avec les variables d'environnement Docker.
     *
     * @throws Exception si la connexion échoue
     */
    public static function initialize(): void
    {
        // Récupération des paramètres depuis les variables d'environnement
        $host = getenv('MONGO_HOST') ?: 'mongodb';
        $port = getenv('MONGO_PORT') ?: '27017';
        $dbName = getenv('MONGO_DATABASE') ?: 'test';
        $username = getenv('MONGO_USERNAME') ?: null;
        $password = getenv('MONGO_PASSWORD') ?: null;

        // Construction de l'URI MongoDB
        $uri = $username && $password
            ? "mongodb://{$username}:{$password}@{$host}:{$port}"
            : "mongodb://{$host}:{$port}";

        try {
            self::$client = new Client($uri);
            self::$databaseName = $dbName;

            // Test de connexion : lister les bases pour vérifier que ça fonctionne
            self::$client->listDatabases();
            error_log("MongoDB: Connexion réussie à la base '{$dbName}'.");
        } catch (Exception $e) {
            error_log("MongoDB: Erreur de connexion: " . $e->getMessage());
            throw new Exception("Impossible de se connecter à MongoDB: " . $e->getMessage());
        }
    }

    /**
     * Retourne le client MongoDB.
     *
     * @return Client
     * @throws Exception si initialize() n'a pas été appelé
     */
    public static function getClient(): Client
    {
        if (self::$client === null) {
            throw new Exception("Connexion MongoDB non initialisée. Appelez initialize() d'abord.");
        }
        return self::$client;
    }

    /**
     * Retourne la base de données MongoDB.
     *
     * @return Database
     * @throws Exception si initialize() n'a pas été appelé
     */
    public static function getDatabase(): Database
    {
        if (self::$databaseName === null) {
            throw new Exception("Nom de base de données non défini. Appelez initialize() d'abord.");
        }
        return self::getClient()->selectDatabase(self::$databaseName);
    }
}