<?php

namespace App\Repository;

use App\Database\DbConnection;
use App\Entity\Registration;
use PDO;

class RegistrationsRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = DbConnection::getPdo();
    }

    public function getTotalParticipantsForWorkshop(int $workshopId): int
    {
        $stmt = $this->pdo->prepare(
            "SELECT SUM(participants) AS total 
            FROM registrations 
            WHERE workshop_id = :workshop_id"
        );
        $stmt->execute(['workshop_id' => $workshopId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) ($result['total'] ?? 0);
    }

    public function isUserRegistered(int $userId, int $workshopId): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) FROM registrations 
            WHERE user_id = :user_id AND workshop_id = :workshop_id"
        );
        $stmt->execute([
            'user_id' => $userId,
            'workshop_id' => $workshopId
        ]);
        return $stmt->fetchColumn() > 0;
    }

    public function addRegistration(Registration $registration): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO registrations (user_id, workshop_id, participants)
        VALUES (:user_id, :workshop_id, :participants)
        ON DUPLICATE KEY UPDATE 
            participants = VALUES(participants),
            registered_at = NOW()"
        );
        return $stmt->execute([
            'user_id' => $registration->getUserId(),
            'workshop_id' => $registration->getWorkshopId(),
            'participants' => $registration->getParticipants()
        ]);
    }

    public function removeRegistration(int $userId, int $workshopId): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM registrations 
        WHERE user_id = :user_id AND workshop_id = :workshop_id"
        );
        return $stmt->execute([
            'user_id' => $userId,
            'workshop_id' => $workshopId
        ]);
    }

    public function getUserRegistrations(int $userId): array
    {
        $stmt = $this->pdo->prepare("
        SELECT r.workshop_id, r.participants, w.name, w.level, w.description, w.date
        FROM registrations r
        INNER JOIN workshops w ON r.workshop_id = w.id
        WHERE r.user_id = :user_id
        ORDER BY w.date ASC
    ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
