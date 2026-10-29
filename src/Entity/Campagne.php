<?php

namespace App\Entity;

use App\Enum\Periodicite;
use App\Repository\CampagneRepository;
use App\Trait\DateTimeTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CampagneRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'campagne' => Campagne::class,
    'epargne' => Epargne::class,
    'tontine' => Tontine::class,
    'pret' => Pret::class,
])]
class Campagne
{
    use DateTimeTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateFin = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(enumType: Periodicite::class)]
    private ?Periodicite $periodicite = null;

    #[ORM\Column(length: 50)]
    private ?string $jourRencontre = null;

    #[ORM\Column]
    private ?float $contribCaissePresent = null;

    #[ORM\Column]
    private ?float $contribCaisseAbsent = null;

    #[ORM\Column]
    private ?float $amendeJournaliere = null;

    /**
     * @var Collection<int, ModeCotisation>
     */
    #[ORM\OneToMany(targetEntity: ModeCotisation::class, mappedBy: 'campagne')]
    private Collection $modeCotisations;

    #[ORM\ManyToOne(inversedBy: 'campagnes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Exercice $exercice = null;

    public function __construct()
    {
        $this->modeCotisations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeImmutable
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeImmutable $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTime
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTime $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
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

    public function getPeriodicite(): ?Periodicite
    {
        return $this->periodicite;
    }

    public function setPeriodicite(Periodicite $periodicite): static
    {
        $this->periodicite = $periodicite;

        return $this;
    }

    public function getJourRencontre(): ?string
    {
        return $this->jourRencontre;
    }

    public function setJourRencontre(string $jourRencontre): static
    {
        $this->jourRencontre = $jourRencontre;

        return $this;
    }

    public function getContribCaissePresent(): ?float
    {
        return $this->contribCaissePresent;
    }

    public function setContribCaissePresent(float $contribCaissePresent): static
    {
        $this->contribCaissePresent = $contribCaissePresent;

        return $this;
    }

    public function getContribCaisseAbsent(): ?float
    {
        return $this->contribCaisseAbsent;
    }

    public function setContribCaisseAbsent(float $contribCaisseAbsent): static
    {
        $this->contribCaisseAbsent = $contribCaisseAbsent;

        return $this;
    }

    public function getAmendeJournaliere(): ?float
    {
        return $this->amendeJournaliere;
    }

    public function setAmendeJournaliere(float $amendeJournaliere): static
    {
        $this->amendeJournaliere = $amendeJournaliere;

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
            $modeCotisation->setCampagne($this);
        }

        return $this;
    }

    public function removeModeCotisation(ModeCotisation $modeCotisation): static
    {
        if ($this->modeCotisations->removeElement($modeCotisation)) {
            if ($modeCotisation->getCampagne() === $this) {
                $modeCotisation->setCampagne(null);
            }
        }

        return $this;
    }

    public function getExercice(): ?Exercice
    {
        return $this->exercice;
    }

    public function setExercice(?Exercice $exercice): static
    {
        $this->exercice = $exercice;

        return $this;
    }
}