<?php

namespace App\Entity;

class Favorite
{
    private int $userId;
    private int $galleryId;

    public function __construct(int $userId, int $galleryId)
    {
        $this->userId = $userId;
        $this->galleryId = $galleryId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getGalleryId(): int
    {
        return $this->galleryId;
    }
}
