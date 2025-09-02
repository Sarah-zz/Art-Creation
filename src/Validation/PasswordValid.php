<?php
//permet de valider le mot de passe
namespace App\Validation;

class PasswordValid
{
    public static function isSecure(string $password): bool
    {
        // au moins 8 caractères
        if (strlen($password) < 8) {
            return false;
        }
        // au moins une majuscule
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }
        // au moins une minuscule
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }
        // au moins un chiffre
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }
        // au moins un caractère spécial
        if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
            return false;
        }
        return true;
    }

    public static function getSecurityDescription(): string
    {
        return "Au moins 8 caractères, incluant une majuscule, une minuscule, un chiffre et un caractère spécial.";
    }
}
