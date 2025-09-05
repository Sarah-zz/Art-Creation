<?php

namespace App\Repository;

use App\Database\DbConnection;
use App\Entity\Workshop;
use PDO;
use DateTimeImmutable;

class WorkshopsRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = DbConnection::getPdo();
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM workshops ORDER BY date ASC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $workshops = [];
        foreach ($rows as $row) {
            $workshop = new Workshop();
            $workshop->setId($row['id'])
                ->setName($row['name'])
                ->setLevel($row['level'])
                ->setDate(new DateTimeImmutable($row['date']))
                ->setMaxPlaces($row['max_places'])
                ->setDescription($row['description'] ?? null)
                ->setDuration($row['duration'] ?? null);

            $workshops[] = $workshop;
        }
        return $workshops;
    }

    public function findById(int $id): ?Workshop
    {
        $stmt = $this->pdo->prepare("SELECT * FROM workshops WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $workshop = new Workshop();
        $workshop->setId($row['id'])
            ->setName($row['name'])
            ->setLevel($row['level'])
            ->setDate(new DateTimeImmutable($row['date']))
            ->setMaxPlaces($row['max_places'])
            ->setDescription($row['description'] ?? null)
            ->setDuration($row['duration'] ?? null);

        return $workshop;
    }

    public function create(Workshop $workshop): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO workshops (name, level, date, max_places, description)
            VALUES (:name, :level, :date, :max_places, :description)
        ");

        return $stmt->execute([
            'name' => $workshop->getName(),
            'level' => $workshop->getLevel(),
            'date' => $workshop->getDate()->format('Y-m-d H:i:s'),
            'max_places' => $workshop->getMaxPlaces(),
            'description' => $workshop->getDescription()
        ]);
    }

    public function update(Workshop $workshop): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE workshops 
            SET name = :name, level = :level, date = :date, max_places = :max_places, description = :description
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $workshop->getId(),
            'name' => $workshop->getName(),
            'level' => $workshop->getLevel(),
            'date' => $workshop->getDate()->format('Y-m-d H:i:s'),
            'max_places' => $workshop->getMaxPlaces(),
            'description' => $workshop->getDescription()
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM workshops WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    private function mapRowToWorkshop(array $row): Workshop
    {
        $workshop = new Workshop();
        $workshop->setId((int) $row['id'])
            ->setName($row['name'])
            ->setLevel($row['level'])
            ->setDate($row['date'])
            ->setMaxPlaces((int) $row['max_places'])
            ->setDescription($row['description']);

        return $workshop;
    }

    public function getTotalRegistered(int $workshopId): int
    {
        $stmt = $this->pdo->prepare(
            "SELECT SUM(participants) as total FROM registrations WHERE workshop_id = :id"
        );
        $stmt->execute(['id' => $workshopId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['total'] ?? 0);
    }

}
