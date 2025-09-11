<?php

namespace App\Repository;

use App\Database\DbConnection;
use App\Entity\User;
use PDO;
use PDOException;

class UserRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = DbConnection::getPdo();
    }

    // Vérifie si email ou pseudo existe déjà
    public function emailOrPseudoExists(string $email, string $pseudo): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email OR pseudo = :pseudo");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }

    // Enregistre un nouvel utilisateur
    public function register(User $user): bool
    {
        try {
            $stmt = $this->pdo->prepare("
            INSERT INTO users (firstname, lastname, pseudo, email, password, role)
            VALUES (:firstname, :lastname, :pseudo, :email, :password, :role)
        ");

            $firstname = $user->getFirstname();
            $lastname = $user->getLastname();
            $pseudo = $user->getPseudo();
            $email = $user->getEmail();
            $hashedPassword = $user->getPassword();
            $role = $user->getRole();

            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':pseudo', $pseudo);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':role', $role, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            throw $e;
        }
    }


    // Récupère un utilisateur par email
    public function getUserByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new User($data) : null;
    }

    public function getUserByPseudo(string $pseudo): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE pseudo = :pseudo");
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new User($data) : null;
    }
    public function getUserById(int $id): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new User($data) : null;
    }

}
