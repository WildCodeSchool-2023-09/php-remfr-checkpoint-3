<?php

namespace App\Service;

use App\Entity\Tile;
use App\Repository\TileRepository;

class MapManager
{
    public function __construct(private TileRepository $tileRepository){}

    public function tileExists(int $x, int $y)
    {
        $tileExist = true;
        
        $tile = $this->tileRepository->findOneBy(["coordX" => $x, "coordY" => $y]);

        if(empty($tile)) {
            $tileExist = false;
        }

        return $tileExist;
    }

    public function getRandomIsland(): Tile
    {
        $tilesIsland = $this->tileRepository->findBy(["type" => "island"]);

        $randomTileIsland = array_rand($tilesIsland);

        return $tilesIsland[$randomTileIsland];
    }
}