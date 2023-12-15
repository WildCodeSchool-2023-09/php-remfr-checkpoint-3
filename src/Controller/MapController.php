<?php

namespace App\Controller;

use App\Service\MapManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
    
    #[Route('/start', name: 'start')]
    public function start(BoatRepository $boatRepository, MapManager $mapManager, EntityManagerInterface $entityManager): Response
    {
        /** Reset the boat's coordinates to 0,0 */
        $boat = $boatRepository->findOneBy([]);
        if ($boat) {
            $boat->setCoordX(0);
            $boat->setCoordY(0);
            $entityManager->flush();
        }

       /** Put the treasure on a random island */
        $randomIsland = $mapManager->getRandomIsland();
        if ($randomIsland) {
            $randomIsland->setHasTreasure(true);
            $entityManager->persist($randomIsland);
            $entityManager->flush();
        }
        return $this->redirectToRoute('map');
    }

}
