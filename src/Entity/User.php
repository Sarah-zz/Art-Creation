<?php

namespace App\Entity;

class User
{
    public const ROLE_UTILISATEUR_ID = 1;
    public const ROLE_ADMIN_ID = 2;

    private ?int $id;
    private string $firstname;
    private string $lastname;
    private string $pseudo;
    private string $email;
    private string $password;
    private int $role;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->firstname = $data['firstname'] ?? '';
        $this->lastname = $data['lastname'] ?? '';
        $this->pseudo = $data['pseudo'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->role = $data['role'] ?? self::ROLE_UTILISATEUR_ID;
    }

    //Getters
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getFirstname(): string
    {
        return $this->firstname;
    }
    public function getLastname(): string
    {
        return $this->lastname;
    }
    public function getPseudo(): string
    {
        return $this->pseudo;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function getRole(): int
    {
        return $this->role;
    }

    //Setters
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }
    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;
        return $this;
    }
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }
    public function setRole(int $role): self
    {
        $this->role = $role;
        return $this;
    }

    //Vérifications simples
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN_ID;
    }

    public function isUtilisateur(): bool
    {
        return $this->role === self::ROLE_UTILISATEUR_ID;
    }
}
