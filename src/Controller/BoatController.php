<?php

namespace App\Controller;

use App\Service\MapManager;
use App\Repository\BoatRepository;
use App\Repository\TileRepository;
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

    #[Route('/direction/{direction}', name: 'direction')]
    public function moveDirection(string $direction,
    BoatRepository $boatRepository, EntityManagerInterface $entityManager, MapManager $mapManager, TileRepository $tileRepository)
    {
        $correctDirection = ['N', 'S', 'O', 'E'];

        if (!in_array($direction, $correctDirection)) {
            return $this->redirectToRoute('404');
        }

        $boat = $boatRepository->findOneBy([]);       

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

        if (!$mapManager->tileExists($boat->getCoordX(), $boat->getCoordY(), $tileRepository)) {
            $this->addFlash('danger', 'Hors map');
        }

        $entityManager->flush();

        return $this->redirectToRoute('map');


    }
}
