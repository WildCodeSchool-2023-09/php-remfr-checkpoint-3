<?php

namespace App\Controller;

use App\Service\MapManager;
use App\Repository\BoatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/boat')]
class BoatController extends AbstractController
{
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

    #[Route('/direction/{direction}', name: 'direction')]
    public function moveDirection(string $direction, BoatRepository $boatRepository, EntityManagerInterface $entityManager, MapManager $mapManager)
    {
        $move = $boatRepository->findOneBy([]);
        
        
            if ($direction === "N") {
                $move->setCoordY($move->getCoordY() - 1);
            } elseif ( $direction === "S") {
                $move->setCoordY($move->getCoordY() + 1);
            } elseif ($direction === "W") {
                $move->setCoordX($move->getCoordX() - 1);
            } elseif ($direction === 'E') {
                $move->setCoordX($move->getCoordX() + 1);
            }
                $entityManager->flush();
        
        if ($mapManager->tileExists($move->getCoordX(), $move->getCoordY())) {
            $this->addFlash('warning', 'Oups vous etes hors map!');
        } else {
            
        }
        return $this->redirectToRoute('map');
    }
}
