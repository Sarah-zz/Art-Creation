<?php

namespace App\Controller;

use App\Database\MongoDbConnection;

class GalleryController
{
    public function index()
    {
        $images = [
            ['url' => 'https://cdn.pixabay.com/photo/2021/06/02/21/37/abstract-6305508_1280.jpg', 'title' => 'Red', 'id' => 1],
            ['url' => 'https://cdn.pixabay.com/photo/2021/08/18/19/20/painting-6556384_1280.jpg', 'title' => 'Lac rose', 'id' => 2],
            ['url' => 'https://cdn.pixabay.com/photo/2018/08/19/07/05/background-3616101_1280.jpg', 'title' => 'La Plage', 'id' => 3],
            ['url' => 'https://cdn.pixabay.com/photo/2022/09/18/23/54/black-woman-7464238_1280.jpg', 'title' => 'Vent', 'id' => 4],
            ['url' => 'https://cdn.pixabay.com/photo/2021/08/18/19/26/background-6556413_1280.jpg', 'title' => 'Mer', 'id' => 5],
            ['url' => 'https://media.istockphoto.com/id/485991870/fr/vectoriel/jus-dorange.jpg?s=612x612&w=0&k=20&c=Dxf6-v2chZJtKGALkJEd9gD1YBY5Es25iv_yk5Udvzs=', 'title' => 'Feu', 'id' => 6],
        ];

        return ['images' => $images];
    }

    /* Suivi d'un clic sur un tableau */
    public function trackClick(int $tableauId): void
    {
        try {
            $db = MongoDbConnection::getDatabase();
            $clicsCollection = $db->selectCollection('clics');

            $clicsCollection->insertOne([
                'tableau_id' => $tableauId,
                'date' => new \MongoDB\BSON\UTCDateTime()
            ]);
        } catch (\Exception $e) {
            error_log("Erreur MongoDB lors de l'insertion d'un clic : " . $e->getMessage());
        }
    }
}
