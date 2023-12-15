<?php

namespace App\Repository;

class MapManager {
    private $map;

    public function tileExists($x, $y) {

        return isset($this->map[$x][$y]);
    }
}
        