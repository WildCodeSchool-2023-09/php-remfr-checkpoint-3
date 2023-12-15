<?php

namespace App\Service;

use App\Repository\TileRepository;

class MapManager
{
    public function tileExiste($x, $y, TileRepository $tileRepository)
    {
        $tile = $tileRepository->findOneBy([
            'coordX' => $x,
            'coordY' => $y
        ]);

        if ($tile) {
            return true;
        } else {
            return false;
        }
    }

    public function getRandomIsland(TileRepository $tileRepository)
    {
        $tile[] = $tileRepository->findOneBy([
            'type' => 'island'
        ]);

        $randomTile = $tile[array_rand($tile)];
        
        return $randomTile;
    }

    public function deleteTreasure(TileRepository $tileRepository)
    {
        $tile = $tileRepository->findOneBy([
            'asTreasure' => true
        ]);

        if($tile){
        $tile->setAsTreasure(false);
        }
    }

    public function checkTreasure($x, $y, TileRepository $tileRepository)
    {
        $tile = $tileRepository->findOneBy([
            'coordX' => $x,
            'coordY' => $y
        ]);

        if ($tile->getAsTreasure()) {
            return true;
        } else {
            return false;
        }
    }
}