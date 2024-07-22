<?php

namespace App\Entity;

use App\Repository\DirecteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DirecteurRepository::class)]
class Directeur extends Utilisateur
{
    /**
     * @var Collection<int, Departement>
     */
    #[ORM\OneToMany(targetEntity: Departement::class, mappedBy: 'directeur')]
    private Collection $departement;

    /**
     * @var Collection<int, Vehicule>
     */
    #[ORM\OneToMany(targetEntity: Vehicule::class, mappedBy: 'directeur')]
    private Collection $idVehicule;

    public function __construct()
    {
        parent::__construct();
        $this->setRole('ROLE_DIRECTEUR');
        $this->departement = new ArrayCollection();
        $this->idVehicule = new ArrayCollection();
    }

    /**
     * @return Collection<int, Departement>
     */
    public function getDepartement(): Collection
    {
        return $this->departement;
    }

    public function addDepartement(Departement $departement): static
    {
        if (!$this->departement->contains($departement)) {
            $this->departement->add($departement);
            $departement->setDirecteur($this);
        }

        return $this;
    }

    public function removeDepartement(Departement $departement): static
    {
        if ($this->departement->removeElement($departement)) {
            // set the owning side to null (unless already changed)
            if ($departement->getDirecteur() === $this) {
                $departement->setDirecteur(null);
            }
        }

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
            $idVehicule->setDirecteur($this);
        }

        return $this;
    }

    public function removeIdVehicule(Vehicule $idVehicule): static
    {
        if ($this->idVehicule->removeElement($idVehicule)) {
            // set the owning side to null (unless already changed)
            if ($idVehicule->getDirecteur() === $this) {
                $idVehicule->setDirecteur(null);
            }
        }

        return $this;
    }
}
