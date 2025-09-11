<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\RegistrationsRepository;
use App\Validation\PasswordValid;

class UserService
{
    private UserRepository $userRepo;
    private RegistrationsRepository $registrationsRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->registrationsRepo = new RegistrationsRepository();
    }

    /**
     * Inscription d'un utilisateur
     */
    public function registerUser(array $data): array
    {
        $pseudo = trim($data['pseudo'] ?? '');
        $firstname = trim($data['firstname'] ?? '');
        $lastname = trim($data['lastname'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        $errors = [];

        if (empty($pseudo) || empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
            $errors[] = "Tous les champs sont obligatoires.";
        }

        if (!PasswordValid::isSecure($password)) {
            $errors[] = PasswordValid::getSecurityDescription();
        }

        if ($this->userRepo->emailOrPseudoExists($email, $pseudo)) {
            $errors[] = "Email ou pseudo déjà utilisé.";
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $user = new User();
        $user->setPseudo($pseudo)
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setEmail($email)
            ->setPassword(password_hash($password, PASSWORD_DEFAULT))
            ->setRole(User::ROLE_UTILISATEUR_ID);

        // Enregistrement
        $this->userRepo->register($user);

        // On récupère l'ID généré par la base
        $savedUser = $this->userRepo->getUserByEmail($email);

        return ['success' => true, 'user' => $savedUser];
    }

    /**
     * Connexion d'un utilisateur
     */
    public function loginUser(string $identifier, string $password): ?User
    {
        $user = $this->userRepo->getUserByEmail($identifier) ?? $this->userRepo->getUserByPseudo($identifier);

        if (!$user || !password_verify($password, $user->getPassword())) {
            return null;
        }

        return $user;
    }

    /**
     * Déconnexion
     */
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        session_destroy();
    }

    /**
     * Récupère les informations pour le profil utilisateur
     */
    public function getUserProfile(int $userId): array
    {
        $user = $this->userRepo->getUserById($userId);
        if (!$user) {
            return [];
        }

        $userWorkshops = $this->registrationsRepo->getUserRegistrations($userId);

        return [
            'user' => [
                'id' => $user->getId(),
                'pseudo' => $user->getPseudo(),
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
                'email' => $user->getEmail(),
                'role' => $user->getRole(),
            ],
            'userWorkshops' => $userWorkshops
        ];
    }
}
