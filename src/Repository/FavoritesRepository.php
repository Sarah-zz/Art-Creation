<?php

namespace App\Repository;

use App\Database\DbConnection;
use App\Entity\Favorite;

class FavoritesRepository
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = DbConnection::getPdo();
    }

    // Vérifie si un favori existe
    public function isFavorite(Favorite $favorite): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) FROM favorites WHERE user_id = :user_id AND gallery_id = :gallery_id"
        );
        $stmt->execute([
            'user_id' => $favorite->getUserId(),
            'gallery_id' => $favorite->getGalleryId()
        ]);
        return (bool) $stmt->fetchColumn();
    }

    // Ajoute un favori
    public function addFavorite(Favorite $favorite): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO favorites (user_id, gallery_id) VALUES (:user_id, :gallery_id)"
        );
        $stmt->execute([
            'user_id' => $favorite->getUserId(),
            'gallery_id' => $favorite->getGalleryId()
        ]);
    }

    // Supprime un favori
    public function removeFavorite(Favorite $favorite): void
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM favorites WHERE user_id = :user_id AND gallery_id = :gallery_id"
        );
        $stmt->execute([
            'user_id' => $favorite->getUserId(),
            'gallery_id' => $favorite->getGalleryId()
        ]);
    }

    // Récupère les favoris d’un utilisateur
    public function getUserFavorites(int $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT gallery_id FROM favorites WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
}
