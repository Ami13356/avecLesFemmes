<?php

namespace App\Entity;

use App\Repository\TontineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TontineRepository::class)]
class Tontine extends Campagne
{
    #[ORM\Column]
    private ?int $ordreTirage = null;

    public function getOrdreTirage(): ?int
    {
        return $this->ordreTirage;
    }

    public function setOrdreTirage(int $ordreTirage): static
    {
        $this->ordreTirage = $ordreTirage;

        return $this;
    }
}