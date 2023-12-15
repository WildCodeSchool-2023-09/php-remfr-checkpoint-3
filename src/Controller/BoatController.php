<?php

namespace App\Controller;

use App\Service\MapManager;
use App\Repository\BoatRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/direction/{direction}', methods: ['GET'], requirements: ["direction" => "[N]|[S]|[E]|[W]"], name:'moveDirection')]
    public function moveDirection(
        string $direction,
        BoatRepository $boatRepository,
        EntityManagerInterface $entityManager,
        MapManager $mapManager
    ): Response {

        /** Recherche les coordonnées actuelles du bateau */
        $boat = $boatRepository->findOneBy([]);
        $x = $boat->getCoordX();
        $y = $boat->getCoordY();
        
        if (!$boat) {
            throw $this->createNotFoundException('No boat found');
        }

        /** Mise à jour de la position du bateau en se basant sur la direction 
         * et vérification que la tuile existe sinon afficher un message */
        switch ($direction) {
            case 'N':
                    $boat->setCoordY($boat->getCoordY() - 1);
                break;
            case 'S':
                    $boat->setCoordY($boat->getCoordY() + 1);
                break;
            case 'E':
                    $boat->setCoordX($boat->getCoordX() + 1);
                break;
            case 'W':
                    $boat->setCoordX($boat->getCoordX() - 1);
                break;
        }
        if($mapManager->tileExists($boat->getCoordX(), $boat->getCoordY())) {
            $entityManager->flush();
            $this->addFlash("sucess", "Tile exist");
        } else {
            $this->addFlash("warning", "Tile doesn't exist");
        }
        

        /** Check for treasure after each moves */
        if ($mapManager->checkTreasure($boat)) {
            $this->addFlash('success', 'Congratulations, you found the treasure!');
        }

        return $this->redirectToRoute('map');
    }
}
