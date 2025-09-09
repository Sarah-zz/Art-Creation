<?php
namespace App\Controller;

use App\Repository\WorkshopsRepository;
use App\Repository\GalleryRepository;

class AdminController
{
    private WorkshopsRepository $workshopsRepo;
    private GalleryRepository $galleryRepo;

    public function __construct()
    {
        $this->workshopsRepo = new WorkshopsRepository();
        $this->galleryRepo = new GalleryRepository();
    }

    public function dashboard(): array
    {
        // Récupérer tous les ateliers et convertir en tableau associatif
        $workshops = array_map(function ($w) {
            return [
                'id' => $w->getId(),
                'name' => $w->getName(),
                'date' => $w->getDate()->format('Y-m-d H:i'),
                'level' => $w->getLevel(),
                'max_places' => $w->getMaxPlaces(),
                'registered' => $this->workshopsRepo->getTotalRegistered($w->getId())
            ];
        }, $this->workshopsRepo->findAll());

        // Récupérer toutes les images (déjà tableau associatif)
        $images = $this->galleryRepo->findAll();

        return [
            'view' => __DIR__ . '/../View/profiladmin.php',
            'workshops' => $workshops,
            'images' => $images
        ];
    }
}
