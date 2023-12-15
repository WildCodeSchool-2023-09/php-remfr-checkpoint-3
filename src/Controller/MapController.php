<?php

namespace App\Controller;

use App\Entity\Tile;
use App\Service\MapManager;
use App\Repository\BoatRepository;
use App\Repository\TileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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


        $boatCoordX = $boat->getCoordX();
        $boatCoordY = $boat->getCoordY();
        $boatTile = $map[$boatCoordX][$boatCoordY]->getType();

        return $this->render('map/index.html.twig', [
            'map'  => $map ?? [],
            'boat' => $boat,
            'boatTile' => $boatTile,
        ]);
    }

    #[Route('/start', name:'start')]
    public function start(
        BoatRepository $boatRepository, 
        TileRepository $tileRepository,
        MapManager $mapManager,
        EntityManagerInterface $entityManager
        )
    {

        $boat = $boatRepository->findOneBy([]);

        $boat->setCoordX(0);
        $boat->setCoordY(0);
        $entityManager->persist($boat);

        $tiles = $tileRepository->findBy(["type" => "island"]);

        foreach($tiles as $tile) {
            $tile->setAsTreasure(false);
            $entityManager->persist($tile);
        }

        $entityManager->flush();

        $randomTileTreasure = $mapManager->getRandomIsland($tileRepository);

        $randomTileTreasure->setAsTreasure(true);
        $entityManager->persist($randomTileTreasure);
        $entityManager->flush();

        return $this->redirectToRoute('map');

    }
}
