<?php

namespace App\Service;

use App\Repository\TileRepository;
use App\Entity\Tile;
use App\Entity\Boat;

class MapManager
{
    private TileRepository $tileRepository;

    public function __construct(TileRepository $tileRepository)
    {
        $this->tileRepository = $tileRepository;
    }

    public function tileExists(int $x, int $y): bool
    {
        return $this->tileRepository->findOneBy(['coordX' => $x, 'coordY' => $y]) !== null;
    }

    public function getRandomIsland(): ?Tile
    {
        // obtenir toutes les tuiles de de type île dans un tableau
        $islandTiles = $this->tileRepository->findBy(['type' => 'island']);

        // ile choisie au hasard dans le tableau $islandTiles
        $randomIslandTile = $islandTiles[array_rand($islandTiles)];

        return $randomIslandTile;
    }

    public function checkTreasure(Boat $boat): bool
    {
        // Tile qui contient le trésor
        $tileWithTreasure = $this->getRandomIsland(); 

        // Vrai si le boat est sur les coordonées de la tile qui contient le trésor.
        return $boat->getCoordX() === $tileWithTreasure->getCoordX()
            && $boat->getCoordY() === $tileWithTreasure->getCoordY();
    }

}
