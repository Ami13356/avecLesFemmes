<?php

namespace App\Entity;

use App\Enum\StatutPresence;
use App\Repository\CotisationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CotisationRepository::class)]
class Cotisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column]
    private ?float $contribCaisse = null;

    #[ORM\Column(enumType: StatutPresence::class)]
    private ?StatutPresence $presence = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getContribCaisse(): ?float
    {
        return $this->contribCaisse;
    }

    public function setContribCaisse(float $contribCaisse): static
    {
        $this->contribCaisse = $contribCaisse;

        return $this;
    }

    public function getPresence(): ?StatutPresence
    {
        return $this->presence;
    }

    public function setPresence(StatutPresence $presence): static
    {
        $this->presence = $presence;

        return $this;
    }
}
