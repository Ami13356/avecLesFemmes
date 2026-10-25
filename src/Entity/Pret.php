<?php

namespace App\Entity;

use App\Repository\PretRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PretRepository::class)]
class Pret extends Campagne
{
    #[ORM\Column]
    private ?float $tauxInteretPret = null;

    #[ORM\Column]
    private ?int $dureeMaxMois = null;

    #[ORM\Column]
    private ?float $tauxPenaliteRetard = null;

    public function getTauxInteretPret(): ?float
    {
        return $this->tauxInteretPret;
    }

    public function setTauxInteretPret(float $tauxInteretPret): static
    {
        $this->tauxInteretPret = $tauxInteretPret;

        return $this;
    }

    public function getDureeMaxMois(): ?int
    {
        return $this->dureeMaxMois;
    }

    public function setDureeMaxMois(int $dureeMaxMois): static
    {
        $this->dureeMaxMois = $dureeMaxMois;

        return $this;
    }

    public function getTauxPenaliteRetard(): ?float
    {
        return $this->tauxPenaliteRetard;
    }

    public function setTauxPenaliteRetard(float $tauxPenaliteRetard): static
    {
        $this->tauxPenaliteRetard = $tauxPenaliteRetard;

        return $this;
    }
}