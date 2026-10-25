<?php

namespace App\Entity;

use App\Repository\MembreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MembreRepository::class)]
class Membre extends User
{
    #[ORM\Column(length: 50)]
    private ?string $matricule = null;

    #[ORM\ManyToOne(inversedBy: 'membres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Role $role = null;

    #[ORM\ManyToOne(inversedBy: 'membres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Groupement $groupement = null;

    /**
     * @var Collection<int, ModeCotisation>
     */
    #[ORM\OneToMany(targetEntity: ModeCotisation::class, mappedBy: 'membre')]
    private Collection $modeCotisations;

    /**
     * @var Collection<int, DemandePret>
     */
    #[ORM\OneToMany(targetEntity: DemandePret::class, mappedBy: 'membre')]
    private Collection $demandePrets;

    public function __construct()
    {
        $this->modeCotisations = new ArrayCollection();
        $this->demandePrets = new ArrayCollection();
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getGroupement(): ?Groupement
    {
        return $this->groupement;
    }

    public function setGroupement(?Groupement $groupement): static
    {
        $this->groupement = $groupement;

        return $this;
    }

    /**
     * @return Collection<int, ModeCotisation>
     */
    public function getModeCotisations(): Collection
    {
        return $this->modeCotisations;
    }

    public function addModeCotisation(ModeCotisation $modeCotisation): static
    {
        if (!$this->modeCotisations->contains($modeCotisation)) {
            $this->modeCotisations->add($modeCotisation);
            $modeCotisation->setMembre($this);
        }

        return $this;
    }

    public function removeModeCotisation(ModeCotisation $modeCotisation): static
    {
        if ($this->modeCotisations->removeElement($modeCotisation)) {
            if ($modeCotisation->getMembre() === $this) {
                $modeCotisation->setMembre(null);
            }
        }

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
            $demandePret->setMembre($this);
        }

        return $this;
    }

    public function removeDemandePret(DemandePret $demandePret): static
    {
        if ($this->demandePrets->removeElement($demandePret)) {
            if ($demandePret->getMembre() === $this) {
                $demandePret->setMembre(null);
            }
        }

        return $this;
    }
}