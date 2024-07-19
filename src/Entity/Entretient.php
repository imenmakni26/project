<?php

namespace App\Entity;

use App\Repository\EntretientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntretientRepository::class)]
class Entretient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?float $prix = null;

    /**
     * @var Collection<int, Vehicule>
     */
    #[ORM\ManyToMany(targetEntity: Vehicule::class, mappedBy: 'entretient')]
    private Collection $idVehicule;

    #[ORM\Column]
    private ?bool $archive = null;

    public function __construct()
    {
        $this->idVehicule = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, Vehicule>
     */
    public function getIdVehicule(): Collection
    {
        return $this->idVehicule;
    }

    public function addIdVehicule(Vehicule $idVehicule): static
    {
        if (!$this->idVehicule->contains($idVehicule)) {
            $this->idVehicule->add($idVehicule);
            $idVehicule->addEntretient($this);
        }

        return $this;
    }

    public function removeIdVehicule(Vehicule $idVehicule): static
    {
        if ($this->idVehicule->removeElement($idVehicule)) {
            $idVehicule->removeEntretient($this);
        }

        return $this;
    }

    public function isArchive(): ?bool
    {
        return $this->archive;
    }

    public function setArchive(bool $archive): static
    {
        $this->archive = $archive;

        return $this;
    }
}
