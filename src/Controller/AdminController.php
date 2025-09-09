<?php
namespace App\Controller;

use App\Repository\GalleryRepository;

class AdminController
{
    private GalleryRepository $galleryRepo;

    public function __construct()
    {
        $this->galleryRepo = new GalleryRepository();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Vérifie que l'utilisateur est admin
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
            header('Location: /');
            exit;
        }
    }

    // Dashboard principal
    public function dashboard(): array
    {
        $images = $this->galleryRepo->findAll();

        return [
            'view' => __DIR__ . '/../View/profiladmin.php',
            'images' => $images
        ];
    }

    // --- AJOUTER UNE IMAGE ---
    public function addGallery(): array
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'] ?? '',
                'image' => $_POST['image'] ?? '',
                'description' => $_POST['description'] ?? '',
                'size' => $_POST['size'] ?? ''
            ];

            $this->galleryRepo->insert($data);
            header('Location: /admin');
            exit;
        }

        return [
            'view' => __DIR__ . '/../Form/GalleryForm.php'
        ];
    }


    // --- MODIFIER UNE IMAGE ---
    public function editGallery(): array
    {
        $id = (int) ($_GET['id'] ?? 0);
        $image = $this->galleryRepo->findById($id);
        if (!$image) {
            header('Location: /admin');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'] ?? '',
                'image' => $_POST['image'] ?? '',
                'description' => $_POST['description'] ?? '',
                'size' => $_POST['size'] ?? ''
            ];
            $this->galleryRepo->update($id, $data);
            header('Location: /admin');
            exit;
        }

        return [
            'view' => __DIR__ . '/../Form/GalleryForm.php',
            'image' => $image
        ];
    }



    // --- SUPPRIMER UNE IMAGE ---
    public function deleteGallery(int $id): void
    {
        $this->galleryRepo->delete($id);
        header('Location: /admin');
        exit;
    }
}
