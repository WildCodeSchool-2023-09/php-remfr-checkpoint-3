<?php

namespace App\Controller;

use App\Repository\BoatRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\MapManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/direction/{dir<N|S|W|E>}', name: 'moveDirection')]
    public function moveDirection(
        string $dir,
        BoatRepository $boatRepository,
        EntityManagerInterface $entityManager,
        MapManager $mapManager
    ): Response {
        $boat = $boatRepository->findOneBy([]);
        
        $boatY = $boat->getCoordY();
        $boatX = $boat->getCoordX();
        switch ($dir) {
            case 'N':
                if($mapManager->tileExists(($boatX), ($boatY - 1))) {
                    $boatY-=1;
                    $boat->setCoordY($boatY);
                } else {
                    $this->addFlash("warning", "the destination tile does not exist");
                }
                break;

            case 'S':
                if($mapManager->tileExists(($boatX), ($boatY + 1))) {
                    $boatY+=1;
                    $boat->setCoordY($boatY);
                    } else {
                        $this->addFlash("warning", "the destination tile does not exist");
                    }
                    break;

            case 'E':
                if($mapManager->tileExists(($boatY), ($boatX + 1))) {
                    $boatX+=1;
                    $boat->setCoordX($boatX);
                    } else {
                        $this->addFlash("warning", "the destination tile does not exist");
                    }
                    break;

            case 'W':
                if($mapManager->tileExists(($boatY), ($boatX - 1))) {
                    $boatX-=1;
                    $boat->setCoordX($boatX);
                    } else {
                        $this->addFlash("warning", "the destination tile does not exist");
                    }
                    break;
    
            default:
                break;
        };

        $entityManager->flush();
        
        return $this->redirectToRoute('map');
    }
}
