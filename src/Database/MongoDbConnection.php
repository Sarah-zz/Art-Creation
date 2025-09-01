<?php

namespace App\Database;

use MongoDB\Client;
use MongoDB\Driver\Exception\Exception as MongoDBException;

class MongoDbConnection
{
    private static ?Client $client = null;
    private static string $databaseName;

    public static function initialize(string $uri, string $dbName): void
    {
        try {
            self::$client = new Client($uri);
            self::$databaseName = $dbName;
            self::$client->listDatabases();
            error_log("MongoDB: Connexion réussie à la base de données '$dbName'.");
        } catch (MongoDBException $e) {
            error_log("MongoDB: Erreur de connexion: " . $e->getMessage());
            throw new \Exception("Impossible de se connecter à la base de données MongoDB: " . $e->getMessage());
        }
    }

    public static function getClient(): Client
    {
        if (self::$client === null) {
            throw new \Exception("La connexion MongoDB n'a pas été initialisée. Appelez MongoDbConnection::initialize() d'abord.");
        }
        return self::$client;
    }

    public static function getDatabase(): \MongoDB\Database
    {
        if (self::$client === null) {
            throw new \Exception("La connexion MongoDB n'a pas été initialisée. Appelez MongoDbConnection::initialize() d'abord.");
        }
        return self::$client->selectDatabase(self::$databaseName);
    }
}
