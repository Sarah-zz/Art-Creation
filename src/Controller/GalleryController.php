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
        $images = $this->repository->findAll();
        return ['images' => $images];
    }

    public function trackClick(array $postData): array
    {
        $tableauId = $postData['tableauId'] ?? null;
        $tableauTitle = $postData['tableauTitle'] ?? null; // récupéré depuis le front

        if (!$tableauId) {
            http_response_code(400);
            return ['success' => false, 'error' => 'tableauId manquant'];
        }

        try {
            $db = MongoDbConnection::getDatabase();
            $clicsCollection = $db->selectCollection('clics');

            // Heure actuelle France
            $date = (new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris')))->format(DATE_ATOM);

            // Insère le clic avec id + titre
            $clicsCollection->insertOne([
                'tableau_id' => (int) $tableauId,
                'tableau_title' => $tableauTitle,
                'date' => $date
            ]);

            return [
                'success' => true,
                'clicked_at' => $date
            ];

        } catch (\Exception $e) {
            http_response_code(500);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
