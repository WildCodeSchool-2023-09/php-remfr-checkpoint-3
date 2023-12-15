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
      public function moveDirection(string $direction,

        BoatRepository $boatRepository,
        EntityManagerInterface $entityManager
        ): Response {
                $boat = $boatRepository->findOneBy([]);
            if ($direction ==="N" ){
                $boat->setCoordY($boat->getCoordY() -1);
            }
            if ($direction === "S"){
                $boat->setCoordY($boat->getCoordY() +1);
            }
            if ($direction === "E"){
                $boat->setCoordX($boat->getCoordX() +1);
            }
            if ($direction === "O"){
                $boat->setCoordX($boat->getCoordX() -1);
                if (!in_array($direction, ['N', 'S', 'E', 'W'])) {

                    throw $this->createNotFoundException('Error 404');


}
       return $this->redirectToRoute("map");

        }

}
}