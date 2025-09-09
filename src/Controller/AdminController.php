<?php
namespace App\Controller;

use App\Repository\GalleryRepository;
use App\Repository\FavoritesRepository;

class AdminController
{
    private GalleryRepository $galleryRepo;
    private FavoritesRepository $favoritesRepo;

    public function __construct()
    {
        $this->galleryRepo = new GalleryRepository();
        $this->favoritesRepo = new FavoritesRepository();

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
        // Récupérer tous les tableaux (galerie)
        $images = $this->galleryRepo->findAll();

        // --- Top clics (MongoDB) ---
        $topClics = [];
        try {
            $db = \App\Database\MongoDbConnection::getDatabase();
            $clicsCollection = $db->selectCollection('clics');

            $agg = $clicsCollection->aggregate([
                [
                    '$group' => [
                        '_id' => [
                            'id' => '$tableau_id',
                            'title' => '$tableau_title'
                        ],
                        'total_clics' => ['$sum' => 1]
                    ]
                ],
                ['$sort' => ['total_clics' => -1]],
                ['$limit' => 10]
            ]);

            foreach ($agg as $item) {
                $topClics[] = [
                    'tableau_id' => $item['_id']['id'],
                    'tableau_title' => $item['_id']['title'] ?? 'Titre inconnu',
                    'total_clics' => $item['total_clics'] ?? 0
                ];
            }
        } catch (\Exception $e) {
            $topClics = [];
        }

        // --- Top favoris (SQL) ---
        $topFavorites = [];
        try {
            $topFavorites = $this->favoritesRepo->countFavoritesByGallery();
        } catch (\Exception $e) {
            $topFavorites = [];
        }

        return [
            'view' => __DIR__ . '/../View/profiladmin.php',
            'images' => $images,
            'topClics' => $topClics,
            'topFavorites' => $topFavorites
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
