<?php

namespace App\Service;

use App\Repository\TileRepository;
use App\Entity\Boat;

class MapManager
{
    private $tileRepository;

    public function __construct(TileRepository $tileRepository)
    {
        $this->tileRepository = $tileRepository;
    }

    public function tileExists(int $x, int $y): bool
    {
        $tile = $this->tileRepository->findOneBy(['coordX' => $x, 'coordY' => $y]);

        return $tile !== null;
    }

    public function getRandomIsland()
    {
        $islandTiles = $this->tileRepository->findBy(['type' => 'island']);

        if (empty($islandTiles)) {
            return null;
        }

        $randomIndex = array_rand($islandTiles);
        return $islandTiles[$randomIndex];

    }

    public function checkTreasure(Boat $boat): bool
    {
        /** Check that this boat is on the tile with the treasure */
        $boatCoordX = $boat->getCoordX();
        $boatCoordY = $boat->getCoordY();
        $currentTile = $this->tileRepository->findOneBy(['coordX' => $boatCoordX, 'coordY' => $boatCoordY]);
        if ($currentTile?->getHasTreasure()) {
            return true;
        }
        return false;
    }
}