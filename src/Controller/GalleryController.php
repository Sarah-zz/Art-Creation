<?php

namespace App\Controller;

class GalleryController
{
    public function index()
    {
        $images = [
            ['url' => 'https://cdn.pixabay.com/photo/2021/06/02/21/37/abstract-6305508_1280.jpg', 'title' => 'Red'],
            ['url' => 'https://cdn.pixabay.com/photo/2021/08/18/19/20/painting-6556384_1280.jpg', 'title' => 'Lac rose'],
            ['url' => 'https://cdn.pixabay.com/photo/2018/08/19/07/05/background-3616101_1280.jpg', 'title' => 'La Plage'],
            ['url' => 'https://via.placeholder.com/400x300.png?text=Art+4', 'title' => 'Peinture 4'],
            ['url' => 'https://cdn.pixabay.com/photo/2018/08/19/07/05/background-3616101_1280.jpg', 'title' => 'Peinture 5'],
            ['url' => 'https://via.placeholder.com/400x300.png?text=Art+6', 'title' => 'Peinture 6'],
        ];

        return ['images' => $images];
    }
}
