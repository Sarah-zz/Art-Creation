<?php

namespace App\Controller;

use App\Repository\GalleryRepository;
use App\Database\MongoDbConnection;

class GalleryController
{
    private GalleryRepository $repository;

    public function __construct()
    {
        $this->repository = new GalleryRepository();
    }

    public function index(): array
    {
        // Récupère toutes les images depuis MySQL
        $images = $this->repository->findAll();
        return ['images' => $images];
    }

    public function trackClick(int $tableauId): void
    {
        try {
            $db = MongoDbConnection::getDatabase();
            $clicsCollection = $db->selectCollection('clics');
            $clicsCollection->insertOne([
                'tableau_id' => $tableauId,
                'date' => new \MongoDB\BSON\UTCDateTime()
            ]);
        } catch (\Exception $e) {
            error_log("Erreur MongoDB lors de l'insertion d'un clic : " . $e->getMessage());
        }
    }
}
