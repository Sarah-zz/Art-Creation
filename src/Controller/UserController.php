<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\User;
use App\Validation\PasswordValid;

class UserController
{
    private UserRepository $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // --- INSCRIPTION ---
    public function register(array $postData): array
    {
        $pseudo = trim($postData['pseudo'] ?? '');
        $firstname = trim($postData['firstname'] ?? '');
        $lastname = trim($postData['lastname'] ?? '');
        $email = trim($postData['email'] ?? '');
        $password = $postData['password'] ?? '';

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
            ->setPassword($password)
            ->setRole(User::ROLE_UTILISATEUR_ID);

        $this->userRepo->register($user);

        // Connexion automatique après inscription
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

    // --- CONNEXION ---
    public function login(array $postData): array
    {
        $identifier = trim($postData['identifier'] ?? '');
        $password = $postData['password'] ?? '';

        if (empty($identifier) || empty($password)) {
            return ['success' => false, 'errors' => ["Veuillez remplir tous les champs."]];
        }

        $user = $this->userRepo->getUserByEmail($identifier) ?? $this->userRepo->getUserByPseudo($identifier);

        if (!$user || !password_verify($password, $user->getPassword())) {
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
        if (empty($_SESSION['user'])) {
            header('Location: /');
            exit;
        }

        $user = $_SESSION['user'];
        $role = $user['role'] ?? 1;

        if ($role == 2) {
            // Redirection automatique vers le dashboard admin
            header('Location: /admin');
            exit;
        }

        $registrationsRepo = new \App\Repository\RegistrationsRepository();
        $userWorkshops = $registrationsRepo->getUserRegistrations($user['id']); // méthode à créer

        return [
            'view' => __DIR__ . '/../View/profiluser.php',
            'user' => $user,
            'userWorkshops' => $userWorkshops
        ];
    }


    // --- DECONNEXION ---
    public function logout(): array
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

        return ['success' => true, 'redirect' => '/'];
    }
}
