<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Tile;
use App\Repository\BoatRepository;
use App\Repository\TileRepository;
use App\Service\MapManager;
use Doctrine\ORM\EntityManagerInterface;

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

        $tileBoat = $tileRepository->findOneBy(['coordX' => $boat->getCoordX(), "coordY" => $boat->getCoordY()]);

        return $this->render('map/index.html.twig', [
            'map'  => $map ?? [],
            'boat' => $boat,
            'titleBoat' => $tileBoat
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
            $tile->setHasTreasure(false);
            $entityManager->persist($tile);
        }

        $entityManager->flush();

        $randomTileTreasure = $mapManager->getRandomIsland();

        $randomTileTreasure->setHasTreasure(true);
        $entityManager->persist($randomTileTreasure);
        $entityManager->flush();

        return $this->redirectToRoute('map');

    }


}
