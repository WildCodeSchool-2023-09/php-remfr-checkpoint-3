<?php

namespace App\service;

use App\Entity\Boat;
use App\Repository\TileRepository;

class MapManager
{
    public function tileExists($coordX,$coordY, TileRepository $tileRepository): bool
    {
        $tile = $tileRepository->findOneBy(['coordX' => $coordX, 'coordY' => $coordY]);

        return $tile !== null;
    }

    public function getRandomIsland($island, TileRepository $tileRepository)
    {
        $islandTiles = $tileRepository->findby(['type'=> $island]);

        $randomKeyIslandTile = array_rand($islandTiles);

        $randomIslandTile = $islandTiles[$randomKeyIslandTile];

        return $randomIslandTile;
    }

    public function checkTreasure(Boat $boat,  TileRepository $tileRepository)
    {
        $currentBoatTile = $tileRepository->findOneBy(['coordX' => $boat->getCoordX(), 'coordY' => $boat->getCoordY()]);

        return $currentBoatTile->isHasTreasure();
    }
}