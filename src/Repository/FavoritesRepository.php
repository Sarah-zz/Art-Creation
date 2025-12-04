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
        $sql = "SELECT g.id, g.title, g.image, g.description
            FROM favorites f
            INNER JOIN gallery g ON f.gallery_id = g.id
            WHERE f.user_id = :user_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }



    //Prépare les stats admin
    public function countFavoritesByGallery(): array
    {
        $sql = "SELECT g.id, g.title, COUNT(f.user_id) AS total_favs
            FROM gallery g
            LEFT JOIN favorites f ON g.id = f.gallery_id
            GROUP BY g.id, g.title
            ORDER BY total_favs DESC";
        return $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

}
