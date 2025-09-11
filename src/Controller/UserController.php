<?php

namespace App\Controller;

use App\Service\UserService;

class UserController
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // --- INSCRIPTION ---
    public function register(array $postData): array
    {
        $result = $this->userService->registerUser($postData);

        if ($result['success']) {
            $user = $result['user'];
            if ($user) {
                $_SESSION['user'] = [
                    'id' => $user->getId(),
                    'pseudo' => $user->getPseudo(),
                    'firstname' => $user->getFirstname(),
                    'lastname' => $user->getLastname(),
                    'email' => $user->getEmail(),
                    'role' => $user->getRole()
                ];
                return ['success' => true, 'redirect' => '/profil'];
            }
        }

        return ['success' => false, 'errors' => $result['errors']];
    }

    // --- CONNEXION ---
    public function login(array $postData): array
    {
        $identifier = trim($postData['identifier'] ?? '');
        $password = $postData['password'] ?? '';

        if (empty($identifier) || empty($password)) {
            return ['success' => false, 'errors' => ["Veuillez remplir tous les champs."]];
        }

        $user = $this->userService->loginUser($identifier, $password);

        if (!$user) {
            return ['success' => false, 'errors' => ["Email, pseudo ou mot de passe incorrect."]];
        }

        $_SESSION['user'] = [
            'id' => $user->getId(),
            'pseudo' => $user->getPseudo(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'email' => $user->getEmail(),
            'role' => $user->getRole()
        ];

        return ['success' => true, 'redirect' => '/profil'];
    }

    // --- PROFIL ---
    public function profil(): array
    {
        // Vérifie que la session et l'ID existent
        if (empty($_SESSION['user']) || empty($_SESSION['user']['id'])) {
            header('Location: /');
            exit;
        }

        $userSession = $_SESSION['user'];
        if ($userSession['role'] == 2) {
            header('Location: /admin');
            exit;
        }

        $profileData = $this->userService->getUserProfile((int) $userSession['id']);

        return [
            'view' => __DIR__ . '/../View/profiluser.php',
            'user' => $profileData['user'] ?? [],
            'userWorkshops' => $profileData['userWorkshops'] ?? []
        ];
    }

    // --- DECONNEXION ---
    public function logout(): array
    {
        $this->userService->logout();
        return ['success' => true, 'redirect' => '/'];
    }
}
