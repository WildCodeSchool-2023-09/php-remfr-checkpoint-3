<?php

namespace App\Controller;

use App\Repository\BoatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\MapManager;

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

    // #[Route('/direction/{direction}', name: 'moveDirection')]
    // public function moveDirection(
    //     string $direction,
    //     BoatRepository $boatRepository,
    //     EntityManagerInterface $entityManager
    // ): Response {
    //      $boat = $boatRepository->findOneBy([]);
        
    //      switch ($direction) {
    //         case 'N':
    //             //if ($boat->getCoordY() > 0) {
    //             $boat->setCoordY($boat->getCoordY() - 1);
    //             //}
    //             break;
    //         case 'S':
    //             //if ($boat->getCoordY() < 5) {
    //             $boat->setCoordY($boat->getCoordY() + 1);
    //             //}
    //             break;
    //         case 'E':
    //             //if ($boat->getCoordX() > 0) {
    //             $boat->setCoordX($boat->getCoordX() - 1);
    //             //}
    //             break;
    //         case 'W':
    //             //if ($boat->getCoordX() < 11) {
    //             $boat->setCoordX($boat->getCoordX() + 1);
    //             //}
    //             break;
    //     }
        
    //     $entityManager->flush();
    //     // http://127.0.0.1:8000/boat/direction/E
    //     return $this->redirectToRoute('map');
    // }

    #[Route('/direction/{direction}', name: 'moveDirection')]
    public function moveDirection(
        string $direction,
        BoatRepository $boatRepository,
        EntityManagerInterface $entityManager,
        MapManager $mapManager
    ): Response {
        $boat = $boatRepository->findOneBy([]);
    
        switch ($direction) {
            case 'N':
                if ($mapManager->tileExists($boat->getCoordX(), $boat->getCoordY() - 1)) {
                    $boat->setCoordY($boat->getCoordY() - 1);
                }
                break;
            case 'S':
                if ($mapManager->tileExists($boat->getCoordX(), $boat->getCoordY() + 1)) {
                    $boat->setCoordY($boat->getCoordY() + 1);
                }
                break;
            case 'E':
                if ($mapManager->tileExists($boat->getCoordX() - 1, $boat->getCoordY())) {
                    $boat->setCoordX($boat->getCoordX() - 1);
                }
                break;
            case 'W':
                if ($mapManager->tileExists($boat->getCoordX() + 1, $boat->getCoordY())) {
                    $boat->setCoordX($boat->getCoordX() + 1);
                }
                break;
        }
    
        $entityManager->flush();
        // appelle la methode start() apres chaque déplacement.
        // $this->start();
        return $this->redirectToRoute('map');
    }

}
