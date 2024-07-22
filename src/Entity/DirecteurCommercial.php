<?php

namespace App\Entity;

use App\Repository\DirecteurCommercialRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DirecteurCommercialRepository::class)]
class DirecteurCommercial extends Utilisateur
{
    #[ORM\Column]
    private ?int $nombreLimiteVehicules = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Departement $departement = null;

    /**
     * @var Collection<int, Vehicule>
     */
    #[ORM\OneToMany(targetEntity: Vehicule::class, mappedBy: 'directeurCommercial')]
    private Collection $idVehicule;

    public function __construct()
    {
        parent::__construct();
        $this->setRole('ROLE_DIRECTEUR_COMMERCIAL');
        $this->idVehicule = new ArrayCollection();
    }

    public function getNombreLimiteVehicules(): ?int
    {
        return $this->nombreLimiteVehicules;
    }

    public function setNombreLimiteVehicules(int $nombreLimiteVehicules): static
    {
        $this->nombreLimiteVehicules = $nombreLimiteVehicules;

        return $this;
    }

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): static
    {
        $this->departement = $departement;

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
            $idVehicule->setDirecteurCommercial($this);
        }

        return $this;
    }

    public function removeIdVehicule(Vehicule $idVehicule): static
    {
        if ($this->idVehicule->removeElement($idVehicule)) {
            // set the owning side to null (unless already changed)
            if ($idVehicule->getDirecteurCommercial() === $this) {
                $idVehicule->setDirecteurCommercial(null);
            }
        }

        return $this;
    }
}
