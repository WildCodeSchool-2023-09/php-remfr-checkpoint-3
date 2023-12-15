<?php

namespace App\Service;

use App\Repository\TileRepository;

class MapManager
{
   public function __construct(TileRepository $tile){
    $this->tile = $tile;
   }

    public function tileExists(int $x, int $y): bool 
    {
        if($this->tile->findOneBy(['coordX' => $x, 'coordY' => $y])){
            return true;
        }
        else{
            return false;
        }
        /*foreach ($tiles as $tile) {
            if($x === $tile->getCoordX() && $y === $tile->getCoordY()){
                return true;
            }
            else {
                return false;
            }
        }*/
    }  
}