<?php

namespace App\Service;

use App\Entity\Boat;
use App\Entity\Tile;
use App\Repository\TileRepository;

class MapManager
{
    private TileRepository $tileRepository;

    public function __construct(TileRepository $tileRepository)
    {
        $this->tileRepository = $tileRepository;
    }

    public function tileExists(int $coordX, int $coordY): bool
    {
        return $this->tileRepository->findOneBy(['coordX' => $coordX, 'coordY' => $coordY]) instanceof Tile;
    }

    public function getRandomIsland(): Tile
    {
        $islands = $this->tileRepository->findBy(['type' => 'island']);

        return $islands[array_rand($islands)];
    }

    public function checkTreasure(Boat $boat): bool
    {
        $tile = $this->tileRepository->findOneBy(['coordX' => $boat->getCoordX(), 'coordY' => $boat->getCoordY()]);

        return $tile->isHasTreasure();
    }
}
