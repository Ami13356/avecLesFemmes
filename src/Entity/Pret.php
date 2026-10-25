<?php

namespace App\Entity;

use App\Repository\PretRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection<int, DemandePret>
     */
    #[ORM\OneToMany(targetEntity: DemandePret::class, mappedBy: 'pret')]
    private Collection $demandePrets;

    public function __construct()
    {
        parent::__construct();
        $this->demandePrets = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, DemandePret>
     */
    public function getDemandePrets(): Collection
    {
        return $this->demandePrets;
    }

    public function addDemandePret(DemandePret $demandePret): static
    {
        if (!$this->demandePrets->contains($demandePret)) {
            $this->demandePrets->add($demandePret);
            $demandePret->setPret($this);
        }

        return $this;
    }

    public function removeDemandePret(DemandePret $demandePret): static
    {
        if ($this->demandePrets->removeElement($demandePret)) {
            if ($demandePret->getPret() === $this) {
                $demandePret->setPret(null);
            }
        }

        return $this;
    }
}