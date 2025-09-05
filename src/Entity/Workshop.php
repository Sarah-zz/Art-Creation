<?php

namespace App\Entity;

use DateTimeImmutable;

class Workshop
{
    private ?int $id = null;
    private string $name;
    private ?string $level = null;
    private DateTimeImmutable $date;
    private int $maxPlaces;
    private ?string $description = null;
    private ?string $duration = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }
    public function setLevel(?string $level): self
    {
        $this->level = $level;
        return $this;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }
    public function setDate(DateTimeImmutable $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getMaxPlaces(): int
    {
        return $this->maxPlaces;
    }
    public function setMaxPlaces(int $maxPlaces): self
    {
        $this->maxPlaces = $maxPlaces;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }
    public function setDuration(?string $duration): self
    {
        $this->duration = $duration;
        return $this;
    }
}
