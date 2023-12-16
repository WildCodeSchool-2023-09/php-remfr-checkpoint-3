<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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

        return $this->render('map/index.html.twig', [
            'map'  => $map ?? [],
            'boat' => $boat,
        ]);
    }

    #[Route('/start', name: 'start')]
    public function start(
        BoatRepository $boatRepository,
        TileRepository $tileRepository,
        MapManager $mapManager,
        EntityManagerInterface $entityManager
    ) {
        $boat = $boatRepository->findOneBy([]);
        $boat->setCoordX(0)->setCoordY(0);


        $island = $tileRepository->findOneBy(['hasTreasure' => true]);
        if ($island) {
            $island->setHasTreasure(false);
            $entityManager->flush();
        }
        $island = $mapManager->getRandomIsland();
        $island->setHasTreasure(true);
        $entityManager->flush();

        return $this->redirectToRoute('map');
    }
}
