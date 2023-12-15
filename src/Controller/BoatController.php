<?php

namespace App\Controller;

use App\Repository\BoatRepository;
use App\Service\MapManager;
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

    #[Route('/direction/{direction}', methods: ['GET'], requirements: ["direction" => "[N]|[S]|[W]|[E]"], name:'direction')]
    public function moveDirection(
        string $direction, 
        BoatRepository $boatRepository,
        EntityManagerInterface $entityManager,
        MapManager $mapManager)
    {
        $boat = $boatRepository->findOneBy([]);
        $directionX = $boat->getCoordX();
        $directionY = $boat->getCoordY();

        switch ($direction) {
            case "N" : 
                if($mapManager->tileExists(($directionX), ($directionY - 1))) {
                    $boat->setCoordY($directionY - 1);
                } else {
                    $this->addFlash("warning", "La tuile n'existe pas");
                }
                break;
            case "S" :
                if($mapManager->tileExists(($directionX), ($directionY + 1))) {
                    $boat->setCoordY($directionY + 1);
                } else {
                    $this->addFlash("warning", "La tuile n'existe pas");
                }
                break;
            case "E" :
                if($mapManager->tileExists(($directionX + 1), ($directionY))) {
                    $boat->setCoordX($directionX + 1);
                } else {
                    $this->addFlash("warning", "La tuile n'existe pas");
                }
                break;
            case "W" :
                if($mapManager->tileExists(($directionX - 1), ($directionY))) {
                    $boat->setCoordX($directionX - 1);
                } else {
                    $this->addFlash("warning", "La tuile n'existe pas");
                }
                break;
        }

        $entityManager->persist($boat);
        $entityManager->flush();

        return $this->redirectToRoute('map');
    }
}
