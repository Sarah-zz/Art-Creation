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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
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
        if (ob_get_length()) {
            ob_clean();
        }

        // Forcer le type JSON
        header('Content-Type: application/json; charset=utf-8');

        $userId = $_SESSION['user']['id'] ?? null;
        if (!$userId) {
            echo json_encode(['success' => false, 'error' => 'Vous devez être connecté pour vous inscrire.']);
            exit;
        }

        $workshopId = $postData['workshop_id'] ?? null;
        $participants = (int) ($postData['participants'] ?? 1);

        if (!$workshopId || $participants < 1 || $participants > 3) {
            echo json_encode(['success' => false, 'error' => 'Données invalides pour l’inscription.']);
            exit;
        }

        if ($this->registrationsRepo->isUserRegistered($userId, $workshopId)) {
            echo json_encode(['success' => false, 'error' => 'Vous êtes déjà inscrit à cet atelier.']);
            exit;
        }

        $workshop = $this->workshopsRepo->findById($workshopId);
        $totalRegistered = $this->registrationsRepo->getTotalParticipantsForWorkshop($workshopId);

        if ($totalRegistered + $participants > $workshop->getMaxPlaces()) {
            echo json_encode(['success' => false, 'error' => 'Pas assez de places disponibles.']);
            exit;
        }

        $registration = new Registration();
        $registration->setUserId($userId)
            ->setWorkshopId($workshopId)
            ->setParticipants($participants);

        $this->registrationsRepo->addRegistration($registration);

        echo json_encode(['success' => true, 'message' => 'Inscription réussie !']);
        exit; // Terminer proprement
    }


    // --- Annulation d’une inscription ---
    public function cancel(): array
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        if (ob_get_length())
            ob_clean();

        // Lire JSON envoyé par le JS
        $postData = json_decode(file_get_contents('php://input'), true) ?? [];

        $userId = $_SESSION['user']['id'] ?? null;
        $workshopId = $postData['workshop_id'] ?? null;

        if (!$userId || empty($workshopId) || $workshopId <= 0) {
            return ['success' => false, 'error' => 'Utilisateur ou workshop manquant'];
        }

        try {
            // Supprimer l'inscription via le repository
            $this->registrationsRepo->removeRegistration($userId, $workshopId);
            // Récupérer le nombre de participants restant pour ce workshop
            $totalRegistered = $this->registrationsRepo->getTotalParticipantsForWorkshop($workshopId);

            return [
                'success' => true,
                'message' => 'Réservation annulée',
                'total_registered' => $totalRegistered
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Impossible d’annuler la réservation : ' . $e->getMessage()
            ];
        }
    }

}
