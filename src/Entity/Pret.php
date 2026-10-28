<?php

namespace App\Entity;

use App\Repository\PretRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PretRepository::class)]
class Pret extends Campagne
{
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