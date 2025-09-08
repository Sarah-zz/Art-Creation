<?php

namespace App\Controller;

use App\Repository\WorkshopsRepository;
use App\Repository\RegistrationsRepository;
use App\Entity\Registration;

class WorkshopsController
{
    private WorkshopsRepository $workshopsRepo;
    private RegistrationsRepository $registrationsRepo;

    public function __construct()
    {
        $this->workshopsRepo = new WorkshopsRepository();
        $this->registrationsRepo = new RegistrationsRepository();
    }

    // --- Page des workshops ---
    public function index(): array
    {
        $workshops = $this->workshopsRepo->findAll();

        $isLoggedIn = !empty($_SESSION['user']['id'] ?? null);
        $userId = $_SESSION['user']['id'] ?? null;

        $workshopsByMonth = [];

        foreach ($workshops as $workshop) {
            $monthName = ucfirst($workshop->getDate()->format('F Y'));

            $totalRegistered = $this->registrationsRepo->getTotalParticipantsForWorkshop($workshop->getId());
            $placesLeft = $workshop->getMaxPlaces() - $totalRegistered;

            $userRegistered = $isLoggedIn && $this->registrationsRepo->isUserRegistered($userId, $workshop->getId());

            $workshopsByMonth[$monthName][] = [
                'workshop' => $workshop,
                'places_left' => $placesLeft,
                'user_registered' => $userRegistered,
                'id' => $workshop->getId(),
                'name' => $workshop->getName(),
                'level' => $workshop->getLevel(),
                'description' => $workshop->getDescription(),
                'date' => $workshop->getDate()->format('Y-m-d H:i'),
                'max_places' => $workshop->getMaxPlaces(),
                'registered' => $totalRegistered
            ];
        }

        return [
            'workshopsByMonth' => $workshopsByMonth,
            'isLoggedIn' => $isLoggedIn
        ];
    }

    // --- Inscription à un workshop ---
    public function register(array $postData): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $userId = $_SESSION['user']['id'] ?? null;
        if (!$userId) {
            echo json_encode(['success' => false, 'error' => 'Vous devez être connecté pour vous inscrire.']);
            return;
        }

        $workshopId = $postData['workshop_id'] ?? null;
        $participants = (int) ($postData['participants'] ?? 1);

        if (!$workshopId || $participants < 1 || $participants > 3) {
            echo json_encode(['success' => false, 'error' => 'Données invalides pour l’inscription.']);
            return;
        }

        // Vérifie si déjà inscrit
        if ($this->registrationsRepo->isUserRegistered($userId, $workshopId)) {
            echo json_encode(['success' => false, 'error' => 'Vous êtes déjà inscrit à cet atelier.']);
            return;
        }

        // Vérifie si assez de places
        $workshop = $this->workshopsRepo->findById($workshopId);
        $totalRegistered = $this->registrationsRepo->getTotalParticipantsForWorkshop($workshopId);

        if ($totalRegistered + $participants > $workshop->getMaxPlaces()) {
            echo json_encode(['success' => false, 'error' => 'Pas assez de places disponibles.']);
            return;
        }

        // Création de l’inscription
        $registration = new Registration();
        $registration->setUserId($userId)
            ->setWorkshopId($workshopId)
            ->setParticipants($participants);

        $this->registrationsRepo->addRegistration($registration);

        echo json_encode(['success' => true, 'message' => 'Inscription réussie !']);
    }


    // --- Annulation d’une inscription ---
    public function cancel(array $postData): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $userId = $_SESSION['user']['id'] ?? null;
        $workshopId = $postData['workshop_id'] ?? null;

        if (!$userId || !$workshopId) {
            echo json_encode(['success' => false, 'error' => 'Utilisateur ou workshop manquant']);
            return;
        }

        $this->registrationsRepo->removeRegistration($userId, $workshopId);

        echo json_encode(['success' => true, 'message' => 'Inscription annulée']);
    }


}
