<?php

namespace App\Controller;

use App\Repository\BoatRepository;
use App\Service\MapManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/boat')]
class BoatController extends AbstractController
{
    #[Route('/move/{x}/{y}', name: 'moveBoat', requirements: ['x' => '\d+', 'y' => '\d+'])]
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

    #[Route('/direction/{direction}', name: 'moveDirection', requirements: ['direction' => '[NSEW]'])]
    public function moveDirection(
        string $direction,
        BoatRepository $boatRepository,
        EntityManagerInterface $entityManager,
        MapManager $mapManager
    ): Response {
        $boat = $boatRepository->findOneBy([]);

        $actualX = $destinationX = $boat->getCoordX();
        $actualY = $destinationY = $boat->getCoordY();

        if ($direction === 'N') {
            $destinationY = $actualY - 1;
        } elseif ($direction === 'S') {
            $destinationY = $actualY + 1;
        } elseif ($direction === 'W') {
            $destinationX = $actualX - 1;
        } elseif ($direction === 'E') {
            $destinationX = $actualX + 1;
        }

        if ($mapManager->tileExists($destinationX, $destinationY)) {
            $boat->setCoordX($destinationX);
            $boat->setCoordY($destinationY);

            $entityManager->flush();

            if ($mapManager->checkTreasure($boat)) {
                $this->addFlash('success', 'Trésor trouvé');
            }
        } else {
            $this->addFlash('danger', 'Sortie de map');
        }

        return $this->redirectToRoute('map');
    }
}
