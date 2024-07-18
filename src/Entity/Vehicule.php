<?php

namespace App\Entity;

use App\Repository\VehiculeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Expr\List_;

#[ORM\Entity(repositoryClass: VehiculeRepository::class)]
class Vehicule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $marque = null;

    #[ORM\Column(length: 255)]
    private ?string $modele = null;

    #[ORM\Column]
    private ?int $annee = null;

    #[ORM\Column(length: 255)]
    private ?string $immatriculation = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Carburant $NumSerieCarburant = null;

    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $couleur = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column(length: 255)]
    private ?string $NumeroSerie = null;

    #[ORM\Column]
    private ?int $kilometrage = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?float $dimensionRoue = null;

    #[ORM\Column(length: 255)]
    private ?string $entretient = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDerniereVidange = null;

    #[ORM\Column(length: 255)]
    private ?string $cartePeage = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Assurance $NumeroAssurance = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Budget $idBudget = null;

    /**
     * @var Collection<int, Historique>
     */
    #[ORM\OneToMany(targetEntity: Historique::class, mappedBy: 'vehicule')]
    private Collection $historiques;

    public function __construct()
    {
        $this->historiques = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): static
    {
        $this->modele = $modele;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): static
    {
        $this->annee = $annee;

        return $this;
    }

    public function getImmatriculation(): ?string
    {
        return $this->immatriculation;
    }

    public function setImmatriculation(string $immatriculation): static
    {
        $this->immatriculation = $immatriculation;

        return $this;
    }

    public function getNumSerieCarburant(): ?Carburant
    {
        return $this->NumSerieCarburant;
    }

    public function setNumSerieCarburant(?Carburant $NumSerieCarburant): static
    {
        $this->NumSerieCarburant = $NumSerieCarburant;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): static
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getNumeroSerie(): ?string
    {
        return $this->NumeroSerie;
    }

    public function setNumeroSerie(string $NumeroSerie): static
    {
        $this->NumeroSerie = $NumeroSerie;

        return $this;
    }

    public function getKilometrage(): ?int
    {
        return $this->kilometrage;
    }

    public function setKilometrage(int $kilometrage): static
    {
        $this->kilometrage = $kilometrage;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDimensionRoue(): ?float
    {
        return $this->dimensionRoue;
    }

    public function setDimensionRoue(float $dimensionRoue): static
    {
        $this->dimensionRoue = $dimensionRoue;

        return $this;
    }

    public function getEntretient(): ?string
    {
        return $this->entretient;
    }

    public function setEntretient(string $entretient): static
    {
        $this->entretient = $entretient;

        return $this;
    }

    public function getDateDerniereVidange(): ?\DateTimeInterface
    {
        return $this->dateDerniereVidange;
    }

    public function setDateDerniereVidange(\DateTimeInterface $dateDerniereVidange): static
    {
        $this->dateDerniereVidange = $dateDerniereVidange;

        return $this;
    }

    public function getCartePeage(): ?string
    {
        return $this->cartePeage;
    }

    public function setCartePeage(string $cartePeage): static
    {
        $this->cartePeage = $cartePeage;

        return $this;
    }

    public function getNumeroAssurance(): ?Assurance
    {
        return $this->NumeroAssurance;
    }

    public function setNumeroAssurance(?Assurance $NumeroAssurance): static
    {
        $this->NumeroAssurance = $NumeroAssurance;

        return $this;
    }

    public function getIdBudget(): ?Budget
    {
        return $this->idBudget;
    }

    public function setIdBudget(?Budget $idBudget): static
    {
        $this->idBudget = $idBudget;
        return $this;
    }

    /**
     * @return Collection<int, Historique>
     */
    public function getHistoriques(): Collection
    {
        return $this->historiques;
    }

    public function addHistorique(Historique $historique): static
    {
        if (!$this->historiques->contains($historique)) {
            $this->historiques->add($historique);
            $historique->setVehicule($this);
        }

        return $this;
    }

    public function removeHistorique(Historique $historique): static
    {
        if ($this->historiques->removeElement($historique)) {
            // set the owning side to null (unless already changed)
            if ($historique->getVehicule() === $this) {
                $historique->setVehicule(null);
            }
        }

        return $this;
    }
}