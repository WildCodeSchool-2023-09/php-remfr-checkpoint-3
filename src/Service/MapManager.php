<?php

namespace App\Service;

use App\Entity\Boat;
use App\Entity\Tile;
use App\Repository\TileRepository;

class MapManager
{
    public function __construct(private TileRepository $tileRepository){}

    public function tileExists(int $y, int $x)
    {
        $tileExists = true;
        $tile = $this->tileRepository->findOneBy(["coordX" => $x, "coordY" => $y]);

        if(empty($tile)) {
            $tileExists = false;
        }

        return $tileExists;
    }
}