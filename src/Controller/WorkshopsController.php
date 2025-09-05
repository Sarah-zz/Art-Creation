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
    public function register(array $postData): array
    {
        $userId = $_SESSION['user']['id'] ?? null;
        if (!$userId) {
            return ['success' => false, 'error' => 'Vous devez être connecté pour vous inscrire.'];
        }

        $workshopId = $postData['workshop_id'] ?? null;
        $participants = (int) ($postData['participants'] ?? 1);

        if (!$workshopId || $participants < 1 || $participants > 3) {
            return ['success' => false, 'error' => 'Données invalides pour l’inscription.'];
        }

        // Vérifie si déjà inscrit
        if ($this->registrationsRepo->isUserRegistered($userId, $workshopId)) {
            return ['success' => false, 'error' => 'Vous êtes déjà inscrit à cet atelier.'];
        }

        // Vérifie si assez de places
        $workshop = $this->workshopsRepo->findById($workshopId);
        $totalRegistered = $this->registrationsRepo->getTotalParticipantsForWorkshop($workshopId);

        if ($participants > 3) {
            return ['success' => false, 'error' => 'Vous ne pouvez réserver que 3 places maximum.'];
        }
        if ($totalRegistered + $participants > $workshop->getMaxPlaces()) {
            return ['success' => false, 'error' => 'Pas assez de places disponibles.'];
        }


        // Création de l’inscription
        $registration = new Registration();
        $registration->setUserId($userId)
            ->setWorkshopId($workshopId)
            ->setParticipants($participants);

        $this->registrationsRepo->addRegistration($registration);

        return ['success' => true, 'message' => 'Inscription réussie !'];
    }

    // --- Annulation d’une inscription ---
    public function cancel(array $postData): array
    {
        $userId = $_SESSION['user']['id'] ?? null;
        $workshopId = $postData['workshopId'] ?? null;

        if (!$userId || !$workshopId) {
            return ['success' => false, 'error' => 'Utilisateur ou workshop manquant'];
        }

        $this->registrationsRepo->removeRegistration($userId, $workshopId);

        return ['success' => true, 'message' => 'Inscription annulée'];
    }

    
}
