<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Tile;
use App\Repository\BoatRepository;
use App\Repository\TileRepository;

class MapController extends AbstractController
{
    #[Route('/map', name: 'map')]
    public function displayMap(BoatRepository $boatRepository, TileRepository $tileRepository): Response
    {
        $tiles = $tileRepository->findAll();

        foreach ($tiles as $tile) {
            $map[$tile->getCoordX()][$tile->getCoordY()] = $tile;
        }

        $boat = $boatRepository->findOneBy([]);

        return $this->render('map/index.html.twig', [
            'map'  => $map ?? [],
            'boat' => $boat,
        ]);
    }

    public function getRandomIsland(
        TileRepository $tileRepository
    ): Tile {

        $island = $this->tileRepository;
        $island -> findBy(["type" => "island"]);
        $treasureIsland = array_rand($island);

        return $island[$treasureIsland];
    }

    #[Route('/start', name:'start')]
    public function start(
        BoatRepository $boatRepository, 
        TileRepository $tileRepository,
        MapManager $mapManager,
        )
    {
        $boat = $boatRepository->findOneBy([]);

        $boat->setCoordX(0);
        $boat->setCoordY(0);
        $entityManager->persist($boat);

        foreach($tiles as $tile) {
            $tile->setHasTreasure(false);
            $entityManager->persist($tile);
            $entityManager->flush();
        }
        $randomTileTreasure = $mapManager->getRandomIsland();

        $randomTileTreasure->setHasTreasure(true);
        $entityManager->persist($randomTileTreasure);
        $entityManager->flush();

        return $this->redirectToRoute('map');

}
}