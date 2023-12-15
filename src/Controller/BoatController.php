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

    #[Route('/direction/{direction}', name: 'moveDirection')]
    public function moveDirection($direction, BoatRepository $boatRepository, EntityManagerInterface $entityManager, MapManager $mapManager, TileRepository $tileRepository)
    {
        $validDirections = ['N', 'O', 'S', 'E'];

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

        // Vérifie si la case existe
        if (!$mapManager->tileExiste($boat->getCoordX(), $boat->getCoordY(), $tileRepository)) {
            $this->addFlash('danger', 'Cette case n\'existe pas !');
            $entityManager->refresh($boat);
        }else{
            $entityManager->flush();
        }
        
        // Vérifie si la direction spécifiée est valide
        if (!in_array($direction, $validDirections)) {
            return $this->redirectToRoute('404');
        }

        return $this->redirectToRoute('map');
    }
}
