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
            ['url' => 'https://cdn.pixabay.com/photo/2022/09/18/23/54/black-woman-7464238_1280.jpg', 'title' => 'Vent'],
            ['url' => 'https://cdn.pixabay.com/photo/2021/08/18/19/26/background-6556413_1280.jpg', 'title' => 'Mer'],
            ['url' => 'https://media.istockphoto.com/id/485991870/fr/vectoriel/jus-dorange.jpg?s=612x612&w=0&k=20&c=Dxf6-v2chZJtKGALkJEd9gD1YBY5Es25iv_yk5Udvzs=', 'title' => 'Feu'],
        ];

        return ['images' => $images];
    }
}
