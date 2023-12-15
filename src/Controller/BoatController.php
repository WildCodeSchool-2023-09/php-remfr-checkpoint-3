<?php

namespace App\Controller;

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

    #[Route('/direction', requirements: ['coord' => 'NSEW'], name: 'moveDirection')]
    public function moveDirection(BoatRepository $boatRepository, EntityManagerInterface $entityManager): Response 
    {
        $boat = $boatRepository->findOneBy();

        if (!$tile) {
            $this->addFlash('warning', 'Le bateau ne peut pas bouger, vous êtes arrivé au bord de la map');
        }
    }
}
