<?php

namespace App\Entity;

use App\Repository\DirectionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DirectionRepository::class)]
class Direction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $direction_N = null;

    #[ORM\Column(nullable: true)]
    private ?int $direction_E = null;

    #[ORM\Column(nullable: true)]
    private ?int $direction_S = null;

    #[ORM\Column(nullable: true)]
    private ?int $direction_W = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?int
    {
        return $this->name;
    }

    public function setName(int $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDirectionN(): ?int
    {
        return $this->direction_N;
    }

    public function setDirectionN(?int $direction_N): static
    {
        $this->direction_N = $direction_N;

        return $this;
    }

    public function getDirectionE(): ?int
    {
        return $this->direction_E;
    }

    public function setDirectionE(?int $direction_E): static
    {
        $this->direction_E = $direction_E;

        return $this;
    }

    public function getDirectionS(): ?int
    {
        return $this->direction_S;
    }

    public function setDirectionS(?int $direction_S): static
    {
        $this->direction_S = $direction_S;

        return $this;
    }

    public function getDirectionW(): ?int
    {
        return $this->direction_W;
    }

    public function setDirectionW(?int $direction_W): static
    {
        $this->direction_W = $direction_W;

        return $this;
    }
}
