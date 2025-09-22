<?php

namespace App\Controller;

use App\Repository\GalleryRepository;
use App\Repository\FavoritesRepository;
use App\Repository\WorkshopsRepository;
use App\Repository\RegistrationsRepository;

class AdminController
{
    private GalleryRepository $galleryRepo;
    private FavoritesRepository $favoritesRepo;
    private WorkshopsRepository $workshopsRepo;
    private RegistrationsRepository $registrationsRepo;

    public function __construct()
    {
        $this->galleryRepo = new GalleryRepository();
        $this->favoritesRepo = new FavoritesRepository();
        $this->workshopsRepo = new WorkshopsRepository();
        $this->registrationsRepo = new RegistrationsRepository();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Vérifie que l'utilisateur est admin
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
            header('Location: /');
            exit;
        }
    }

    // --- DASHBOARD ADMIN ---
    public function dashboard(): array
    {
        $images = $this->galleryRepo->findAll();

        // Top clics MongoDB
        $topClics = [];
        try {
            $db = \App\Database\MongoDbConnection::getDatabase();
            $clicsCollection = $db->selectCollection('clics');
            $agg = $clicsCollection->aggregate([
                [
                    '$group' => [
                        '_id' => ['id' => '$tableau_id', 'title' => '$tableau_title'],
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

        // Top favoris SQL
        $topFavorites = [];
        try {
            $topFavorites = $this->favoritesRepo->countFavoritesByGallery();
        } catch (\Exception $e) {
            $topFavorites = [];
        }

        // Ateliers
        // Ateliers
        $workshops = $this->workshopsRepo->findAll();
        $workshopsData = [];

        $now = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'));

        foreach ($workshops as $w) {
            $workshopDate = $w->getDate();

            // Conversion vers DateTimeImmutable
            if ($workshopDate instanceof \DateTimeImmutable) {
                // rien à faire
            } elseif ($workshopDate instanceof \DateTime) {
                $workshopDate = \DateTimeImmutable::createFromMutable($workshopDate);
            } elseif (is_string($workshopDate)) {
                $workshopDate = new \DateTimeImmutable($workshopDate, new \DateTimeZone('Europe/Paris'));
            } else {
                $workshopDate = $now; // sécurité
            }

            $isPast = $workshopDate < $now;
            $registered = $this->registrationsRepo->getTotalParticipantsForWorkshop($w->getId());

            $workshopsData[] = [
                'id' => $w->getId(),
                'name' => $w->getName(),
                'date' => $workshopDate->format('Y-m-d H:i'),
                'level' => $w->getLevel(),
                'max_places' => $w->getMaxPlaces(),
                'registered' => $registered,
                'places_display' => "{$registered}/{$w->getMaxPlaces()}",
                'isPast' => $isPast
            ];
        }


        return [
            'view' => __DIR__ . '/../View/profiladmin.php',
            'images' => $images,
            'topClics' => $topClics,
            'topFavorites' => $topFavorites,
            'workshops' => $workshopsData
        ];
    }

    // --- CRUD GALERIE ---
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

        return ['view' => __DIR__ . '/../Form/GalleryForm.php'];
    }

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

        return ['view' => __DIR__ . '/../Form/GalleryForm.php', 'image' => $image];
    }

    public function deleteGallery(int $id): void
    {
        $this->galleryRepo->delete($id);
        header('Location: /admin');
        exit;
    }

    // --- CRUD ATELIERS ---
    public function adminWorkshops(): array
    {
        $allWorkshops = $this->workshopsRepo->findAll();
        $workshopsData = [];

        $now = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'));

        foreach ($allWorkshops as $w) {
            // Récupérer la date de l'atelier et la convertir en DateTimeImmutable
            $workshopDate = $w->getDate();
            if ($workshopDate instanceof \DateTimeImmutable) {
                // rien à faire
            } elseif ($workshopDate instanceof \DateTime) {
                $workshopDate = \DateTimeImmutable::createFromMutable($workshopDate);
            } elseif (is_string($workshopDate)) {
                $workshopDate = new \DateTimeImmutable($workshopDate, new \DateTimeZone('Europe/Paris'));
            } else {
                $workshopDate = $now; // sécurité
            }

            // Vérifier si l'atelier est passé
            $isPast = $workshopDate < $now;

            // Nombre d’inscrits
            $registered = $this->registrationsRepo->getTotalParticipantsForWorkshop($w->getId());

            // Construire le tableau de données
            $workshopsData[] = [
                'id' => $w->getId(),
                'name' => $w->getName(),
                'date' => $workshopDate->format('Y-m-d H:i'),
                'level' => $w->getLevel(),
                'max_places' => $w->getMaxPlaces(),
                'registered' => $registered,
                'places_display' => "{$registered}/{$w->getMaxPlaces()}",
                'isPast' => $isPast
            ];
        }

        return [
            'view' => __DIR__ . '/../View/profiladmin.php',
            'workshops' => $workshopsData
        ];
    }


    public function addWorkshop(): array
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $date = $_POST['date'] ?? '';
            $hour = $_POST['hour'] ?? '10';

            try {
                $dateTime = new \DateTimeImmutable("$date $hour:00");
            } catch (\Exception $e) {
                return [
                    'view' => __DIR__ . '/../Form/WorkshopForm.php',
                    'error' => 'Date ou heure invalide.'
                ];
            }

            $workshop = new \App\Entity\Workshop();
            $workshop->setName($_POST['name'] ?? '');
            $workshop->setDate($dateTime);
            $workshop->setLevel($_POST['level'] ?? '');
            $workshop->setMaxPlaces((int) ($_POST['max_places'] ?? 0));
            $workshop->setDescription($_POST['description'] ?? null);

            $this->workshopsRepo->create($workshop);

            $baseUrl = $_ENV['BASE_URL'] ?? '';
            header('Location: ' . $baseUrl . '/admin');
            exit;
        }

        return ['view' => __DIR__ . '/../Form/WorkshopForm.php'];
    }


    public function editWorkshop(): array
    {
        $id = (int) ($_GET['id'] ?? 0);
        $workshop = $this->workshopsRepo->findById($id);

        if (!$workshop) {
            $baseUrl = $_ENV['BASE_URL'] ?? '';
            header('Location: ' . $baseUrl . '/admin/workshops');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $date = $_POST['date'] ?? '';
            $hour = $_POST['hour'] ?? '10';

            try {
                $dateTime = new \DateTimeImmutable("$date $hour:00");
            } catch (\Exception $e) {
                return [
                    'view' => __DIR__ . '/../Form/WorkshopForm.php',
                    'workshop' => $workshop,
                    'error' => 'Date ou heure invalide.'
                ];
            }

            $workshop->setName($_POST['name'] ?? '');
            $workshop->setDate($dateTime);
            $workshop->setLevel($_POST['level'] ?? '');
            $workshop->setMaxPlaces((int) ($_POST['max_places'] ?? 0));
            $workshop->setDescription($_POST['description'] ?? null);

            $this->workshopsRepo->update($workshop);

            $baseUrl = $_ENV['BASE_URL'] ?? '';
            header('Location: ' . $baseUrl . '/admin');
            exit;
        }

        return ['view' => __DIR__ . '/../Form/WorkshopForm.php', 'workshop' => $workshop];
    }


    public function deleteWorkshop(int $id): void
    {
        $this->workshopsRepo->delete($id);
        header('Location: /admin');
        exit;
    }
}
