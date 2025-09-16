<?php

namespace App\Controller;

class HomeController
{
    public function render(): array
    {
        // données a afficher dans la vue
        return [
            'pageTitle'   => "Bienvenue sur Art & Création",
            'subtitle'    => "À propos de Lya",
            'imageSrc'    => "/assets/img/atelier-lya.jpg",
            'imageAlt'    => "Atelier de Lya",
            'buttonLabel' => "Découvrir les œuvres",
            'buttonLink'  => "/galerie",
        ];
    }
}
