<?php
namespace App\Controller;

class WorkshopsController
{
    public function index(): array
    {
        // true pour tester utlisateur connecté
        $isLoggedIn = true;

        // Génération calendrier : un samedi sur deux jusqu'à décembre
        $calendarMonths = [];
        $end = new \DateTime('last day of December');
        $current = new \DateTime('next saturday');

        while ($current <= $end) {
            $monthName = ucfirst($current->format('F Y'));
            $calendarMonths[$monthName][] = $current->format('d/m/Y');
            $current->modify('+2 weeks');
        }

        return [
            'calendarMonths' => $calendarMonths,
            'isLoggedIn' => $isLoggedIn
        ];
    }
}
