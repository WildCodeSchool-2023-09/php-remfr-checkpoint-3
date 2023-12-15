<?php

namespace App\Service;

use App\Repository\TileRepository;

class MapManager
{
    public function tileExists(int $x, int $y, TileRepository $tileRepository): bool
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
}