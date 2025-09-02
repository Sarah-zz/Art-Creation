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

        // Vérifier que tous les champs sont remplis
        if (empty($pseudo) || empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
            $errors[] = "Tous les champs sont obligatoires.";
        }

        // Vérifier mot de passe sécurisé
        if (!PasswordValid::isSecure($password)) {
            $errors[] = PasswordValid::getSecurityDescription();
        }

        // Vérifier que le pseudo ou email n’existe pas déjà
        if ($this->userRepo->emailOrPseudoExists($email, $pseudo)) {
            $errors[] = "Email ou pseudo déjà utilisé.";
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Créer l'utilisateur
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

        return ['success' => true];
    }

    // --- CONNEXION ---
    public function login(array $postData): array
    {
        $identifier = trim($postData['identifier'] ?? '');
        $password = $postData['password'] ?? '';

        $errors = [];

        if (empty($identifier) || empty($password)) {
            $errors[] = "Veuillez remplir tous les champs.";
            return ['success' => false, 'errors' => $errors];
        }

        // Essayer de récupérer l'utilisateur par email
        $user = $this->userRepo->getUserByEmail($identifier);

        // Sinon par pseudo
        if (!$user) {
            $user = $this->userRepo->getUserByPseudo($identifier);
        }

        if (!$user || !password_verify($password, $user->getPassword())) {
            $errors[] = "Email, pseudo ou mot de passe incorrect.";
            return ['success' => false, 'errors' => $errors];
        }

        // Connexion réussie
        $_SESSION['user'] = [
            'id' => $user->getId(),
            'pseudo' => $user->getPseudo(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'email' => $user->getEmail(),
            'role' => $user->getRole()
        ];

        return ['success' => true];
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
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        // Retourner succès + URL pour redirection
        return [
            'success' => true,
            'redirect' => '/' 
        ];
    }
}
