<?php

namespace App\Controller;

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

    #[Route('/direction/{direction}', methods: ['GET'], requirements: ["direction" => "[N]|[S]|[W]|[E]"], name:'direction')]
    public function moveDirection(
        string $direction, 
        BoatRepository $boatRepository,
        EntityManagerInterface $entityManager)
    {
        $boat = $boatRepository->findOneBy([]);


        switch ($direction) {
            case "N" : 
                $boat->setCoordY($boat->getCoordY() - 1);
                break;
            case "S" :
                $boat->setCoordY($boat->getCoordY() + 1);
                break;
            case "E" :
                $boat->setCoordX($boat->getCoordX() + 1);
                break;
            case "W" :
                $boat->setCoordX($boat->getCoordX() - 1);
                break;
        }

        $entityManager->persist($boat);
        $entityManager->flush();

        return $this->redirectToRoute('map');
    }
}
