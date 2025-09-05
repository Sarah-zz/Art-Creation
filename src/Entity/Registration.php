<?php

namespace App\Entity;

class Registration
{
    private int $id;
    private int $userId;
    private int $workshopId;
    private int $participants;

    public function __construct()
    {
    }

    // --- Getters ---
    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getWorkshopId(): int
    {
        return $this->workshopId;
    }

    public function getParticipants(): int
    {
        return $this->participants;
    }

    // --- Setters ---
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function setWorkshopId(int $workshopId): self
    {
        $this->workshopId = $workshopId;
        return $this;
    }

    public function setParticipants(int $participants): self
    {
        $this->participants = $participants;
        return $this;
    }


}
