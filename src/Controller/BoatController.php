<?php

namespace App\Controller;

use App\Repository\BoatRepository;
use App\Repository\TileRepository;
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
        TileRepository $tileRepository,
        EntityManagerInterface $entityManager,
        MapManager $mapManager,
    ): Response {
        $boat = $boatRepository->findOneBy([]);
        
        $boat->setCoordX($x);
        $boat->setCoordY($y);

        $entityManager->flush();

        if($mapManager->checkTreasure($boat)) {
            $this->addFlash("success", "Le capitaine Jack Sparrow à trouvé le trésor !");
        }
        
        return $this->redirectToRoute('map');
    }

    #[Route('/direction/{direction}', methods: ['GET'], requirements: ["direction" => "[N]|[S]|[W]|[E]"], name:'direction')]
    public function moveDirection(
        string $direction, 
        BoatRepository $boatRepository,
        MapManager $mapManager)
    {
        $boat = $boatRepository->findOneBy([]);
        $directionX = $boat->getCoordX();
        $directionY = $boat->getCoordY();

        switch ($direction) {
            case "N" : 
                if($mapManager->tileExists(($directionX), ($directionY - 1))) {
                    $directionY -= 1;
                } else {
                    $this->addFlash("warning", "La tuile n'existe pas");
                }
                break;
            case "S" :
                if($mapManager->tileExists(($directionX), ($directionY + 1))) {
                    $directionY += 1;
                } else {
                    $this->addFlash("warning", "La tuile n'existe pas");
                }
                break;
            case "E" :
                if($mapManager->tileExists(($directionX + 1), ($directionY))) {
                    $directionX += 1;
                } else {
                    $this->addFlash("warning", "La tuile n'existe pas");
                }
                break;
            case "W" :
                if($mapManager->tileExists(($directionX - 1), ($directionY))) {
                    $directionX -= 1;
                } else {
                    $this->addFlash("warning", "La tuile n'existe pas");
                }
                break;
        }

        return $this->redirectToRoute('moveBoat', ['x' => $directionX, 'y' => $directionY]);
    }
}
