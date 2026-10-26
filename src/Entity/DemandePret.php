<?php

namespace App\Entity;

use App\Repository\DemandePretRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandePretRepository::class)]
class DemandePret
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montantDemande = null;

    #[ORM\Column(length: 255)]
    private ?string $motif = null;

    #[ORM\Column(length: 50)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateDemande = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateEcheance = null;

    #[ORM\Column(nullable: true)]
    private ?float $montantAmende = null;

    #[ORM\Column]
    private ?bool $estRembourse = null;

    #[ORM\ManyToOne(inversedBy: 'demandePrets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Membre $membre = null;

    #[ORM\ManyToOne(inversedBy: 'demandePrets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pret $pret = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontantDemande(): ?float
    {
        return $this->montantDemande;
    }

    public function setMontantDemande(float $montantDemande): static
    {
        $this->montantDemande = $montantDemande;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): static
    {
        $this->motif = $motif;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDateDemande(): ?\DateTime
    {
        return $this->dateDemande;
    }

    public function setDateDemande(\DateTime $dateDemande): static
    {
        $this->dateDemande = $dateDemande;

        return $this;
    }

    public function getDateEcheance(): ?\DateTime
    {
        return $this->dateEcheance;
    }

    public function setDateEcheance(\DateTime $dateEcheance): static
    {
        $this->dateEcheance = $dateEcheance;

        return $this;
    }

    public function getMontantAmende(): ?float
    {
        return $this->montantAmende;
    }

    public function setMontantAmende(?float $montantAmende): static
    {
        $this->montantAmende = $montantAmende;

        return $this;
    }

    public function isEstRembourse(): ?bool
    {
        return $this->estRembourse;
    }

    public function setEstRembourse(bool $estRembourse): static
    {
        $this->estRembourse = $estRembourse;

        return $this;
    }

    public function getMembre(): ?Membre
    {
        return $this->membre;
    }

    public function setMembre(?Membre $membre): static
    {
        $this->membre = $membre;

        return $this;
    }

    public function getPret(): ?Pret
    {
        return $this->pret;
    }

    public function setPret(?Pret $pret): static
    {
        $this->pret = $pret;

        return $this;
    }
}
