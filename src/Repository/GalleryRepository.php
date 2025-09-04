<?php
namespace App\Repository;

use App\Database\DbConnection;

class GalleryRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = DbConnection::getPdo();
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM gallery ORDER BY id DESC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM gallery WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $image = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $image ?: null;
    }

    public function insert(array $data): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO gallery (title, image, description, size)
            VALUES (:title, :image, :description, :size)
        ");
        return $stmt->execute([
            'title' => $data['title'],
            'image' => $data['image'],
            'description' => $data['description'] ?? null,
            'size' => $data['size'] ?? null
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE gallery SET title = :title, image = :image, description = :description, size = :size
            WHERE id = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'image' => $data['image'],
            'description' => $data['description'] ?? null,
            'size' => $data['size'] ?? null
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM gallery WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
