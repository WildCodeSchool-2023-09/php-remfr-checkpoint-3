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
}