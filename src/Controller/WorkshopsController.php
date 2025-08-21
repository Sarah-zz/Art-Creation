<?php
namespace App\Controller;
class WorkshopsController
{
    public function index(): array
    {
        // Préparer les données
        $workshops = [
            ['name' => 'Atelier peinture', 'level' => 'Débutant', 'spots' => 5],
            ['name' => 'Atelier peinture', 'level' => 'Intermédiaire', 'spots' => 2],
            ['name' => 'Atelier peinture', 'level' => 'Avancé', 'spots' => 0],
        ];

        $title = 'Liste des ateliers';
        $content = 'Voici la liste des ateliers disponibles :';

        // Retourner les données au routeur
        return [
            'title' => $title,
            'content' => $content,
            'workshops' => $workshops,
        ];
    }
}
