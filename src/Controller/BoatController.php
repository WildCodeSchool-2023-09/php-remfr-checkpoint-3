<?php

namespace App\Controller;

use App\Repository\BoatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Boat;

#[Route('/boat')]
class BoatController extends AbstractController
{
    protected array $cardinal = ['N', 'S', 'E', 'W'];
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

    #[Route('/direction/{direction}', name: 'moveDirection', methods: ['GET'])]
    public function moveDirection(string $direction, BoatRepository $boatRepository,
    EntityManagerInterface $entityManager): Response 
    {
        $boat = $boatRepository->findOneBy([]);
        $boatY = $boat->getCoordY();
        $boatX = $boat->getCoordX();

        if(in_array($direction, $this->cardinal)){
            if($direction === 'N'){
                $boat->setCoordY($boatY -= 1);
            }
            if($direction === 'S'){
                $boat->setCoordY($boatY += 1);
            }
            if($direction === 'E'){
                $boat->setCoordX($boatX += 1);
            }
            if($direction === 'W'){
                $boat->setCoordX($boatX -= 1);
            }
        }
        $entityManager->flush();

        return $this->redirectToRoute('map');
    }
}
