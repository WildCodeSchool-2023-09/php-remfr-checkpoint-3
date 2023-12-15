<?php

namespace App\Service;

use App\Repository\TileRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/*public function tileExists(int $x, int$y, TileRepository $tileRepository) {*/

class MapManager
{
    public function tileExists(int $x, int $y, TileRepository $tileRepository)
    {
    }

    public function getRandomIsland() {
        array_rand($islandType )
    }
}
