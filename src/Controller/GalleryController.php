<?php

namespace App\Controller;

use App\Repository\GalleryRepository;
use App\Repository\FavoritesRepository;
use App\Entity\Favorite;
use App\Database\MongoDbConnection;

class GalleryController
{
    private GalleryRepository $repository;
    private FavoritesRepository $favoritesRepo;

    public function __construct()
    {
        $this->repository = new GalleryRepository();
        $this->favoritesRepo = new FavoritesRepository();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // --- Affiche la galerie avec les favoris de l'utilisateur ---
    public function index(): array
    {
        $images = $this->repository->findAll();
        $favorites = [];

        if (!empty($_SESSION['user']['id'])) {
            $userId = $_SESSION['user']['id'];
            $favorites = $this->favoritesRepo->getUserFavorites($userId);
        }

        return [
            'images' => $images,
            'favorites' => $favorites
        ];
    }

    // --- Toggle favori ---
    public function toggleFavorite(array $postData): array
    {
        $userId = $_SESSION['user']['id'] ?? null;
        $galleryId = $postData['galleryId'] ?? null;

        if (!$userId || !$galleryId) {
            return ['success' => false, 'error' => 'Utilisateur ou galerie manquant'];
        }

        $favorite = new Favorite($userId, (int) $galleryId);

        if ($this->favoritesRepo->isFavorite($favorite)) {
            $this->favoritesRepo->removeFavorite($favorite);
            return ['success' => true, 'isFavorite' => false];
        } else {
            $this->favoritesRepo->addFavorite($favorite);
            return ['success' => true, 'isFavorite' => true];
        }
    }

    // --- Track clic sur un tableau (MongoDB) ---
    public function trackClick(array $postData): array
    {
        $tableauId = $postData['tableauId'] ?? null;
        $tableauTitle = $postData['tableauTitle'] ?? null;

        if (!$tableauId) {
            http_response_code(400);
            return ['success' => false, 'error' => 'tableauId manquant'];
        }

        try {
            $db = MongoDbConnection::getDatabase();
            $clicsCollection = $db->selectCollection('clics');

            $date = (new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris')))->format(DATE_ATOM);

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
