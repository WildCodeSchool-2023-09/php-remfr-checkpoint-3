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

    // étape 4
    #[Route('/direction/{direction}', name: 'moveDirection')]
    public function moveDirection($direction, BoatRepository $boatRepository, EntityManagerInterface $entityManager)
    {
        $validDirections = ['N', 'O', 'S', 'E'];

        $boat = $boatRepository->findOneBy([]);

        // Met à jour les coordonnées en fonction de la direction
        switch ($direction) {
            case 'N':
                $boat->setCoordY($boat->getCoordY() - 1);
                break;
            case 'O':
                $boat->setCoordX($boat->getCoordX() - 1);
                break;
            case 'S':
                $boat->setCoordY($boat->getCoordY() + 1);
                break;
            case 'E':
                $boat->setCoordX($boat->getCoordX() + 1);
                break;
        }
        // Vérifie si la direction spécifiée est valide
        if (!in_array($direction, $validDirections)) {
            return $this->redirectToRoute('404');
        }

        $entityManager->flush();

        return $this->redirectToRoute('map');
    }
}
