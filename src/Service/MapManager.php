<?php

namespace App\Service;

use App\Repository\TileRepository;

class MapManager
{
    public function __construct(private TileRepository $tileRepository)
    {
        
    }
    public function tileExists($x, $y)
    {
        $tile = $this->tileRepository->findOneBy(['coordX' => $x, 'coordY' => $y]);
        
        if ($x !== $tile->getCoordX() || $y !== $tile->getCoordY()) {
            return true;
        }
    }
}