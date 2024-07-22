<?php

namespace App\Entity;

use App\Repository\ResponsabledeflotteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResponsabledeflotteRepository::class)]
class Responsabledeflotte extends Utilisateur
{
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Departement $departement = null;

    /**
     * @var Collection<int, Vehicule>
     */
    #[ORM\OneToMany(targetEntity: Vehicule::class, mappedBy: 'responsabledeflotte')]
    private Collection $idVehicule;

    public function __construct()
    {
        parent::__construct();
        $this->setRole('ROLE_RESPONSABLE_FLOTTE');
        $this->idVehicule = new ArrayCollection();
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
            $idVehicule->setResponsabledeflotte($this);
        }

        return $this;
    }

    public function removeIdVehicule(Vehicule $idVehicule): static
    {
        if ($this->idVehicule->removeElement($idVehicule)) {
            // set the owning side to null (unless already changed)
            if ($idVehicule->getResponsabledeflotte() === $this) {
                $idVehicule->setResponsabledeflotte(null);
            }
        }

        return $this;
    }
}
