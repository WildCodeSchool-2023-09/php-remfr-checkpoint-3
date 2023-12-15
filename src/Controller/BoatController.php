<?php

namespace App\Controller;

use App\Repository\BoatRepository;
use App\Repository\TileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\service\MapManager;


#[Route('/boat')]
class BoatController extends AbstractController
{
    public const DIRECTIONS = [
        'N' => [0, 1],
        'S' => [0, -1],
        'E' => [1, 0],
        'W' => [-1, 0],
    ];

    #[Route('/move/{x<\d+>}/{y<\d+>}', name: 'moveBoat')]
    public function moveBoat(
        int $x,
        int $y,
        BoatRepository $boatRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $boat = $boatRepository->findOneBy([]);
        
        $boat->setCoordX($x);
        $boat->setCoordY($y);

        $entityManager->flush();
        
        return $this->redirectToRoute('map');
    }
    #[Route('/direction/{direction}', name: 'moveDirection')]
    public function moveDirection(
        $direction,
        BoatRepository $boatRepository,
        EntityManagerInterface $entityManager,
        TileRepository $tileRepository,
        MapManager $mapManager
    ): Response
    {
        $boat = $boatRepository->findOneBy([]);

        $directionGiven = self::DIRECTIONS[$direction];
        $possibleX = $boat->getCoordX() + $directionGiven[0];
        $possibleY = $boat->getCoordY() + $directionGiven[1];

        if ($mapManager->tileExists($possibleX, $possibleY, $tileRepository))
        {
            
            $boat->setCoordX($possibleX);
            $boat->setCoordY($possibleY);

            $entityManager->flush();
        }
        else
        {
            $this->addFlash('danger', 'This destination is not available, Moron Jack');
        }
        if ($mapManager->checkTreasure($boat,$tileRepository)) 
        {
            $this->addFlash('success', 'Hey Jack,it is time to pay me some brandy!!! Too much gold for one people');
        }
       
        return $this->redirectToRoute('map');
    }
}
