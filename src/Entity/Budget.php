<?php

namespace App\Entity;

use App\Repository\BudgetRepository;
use App\Entity\Assurance;
use App\Entity\Carburant;
use App\Entity\Entretient;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BudgetRepository::class)]
class Budget
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $MontantAlloue = null;

    #[ORM\Column]
    private ?float $depense = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getMontantAlloue(): ?float
    {
        return $this->MontantAlloue;
    }

    public function setMontantAlloue(float $MontantAlloue): static
    {
        $this->MontantAlloue = $MontantAlloue;

        return $this;
    }

    public function getDepense(): ?float
    {
        return $this->depense;
    }

    public function setDepense(float $depense): static
    {
        $this->depense = $depense;

        return $this;
    }
        public function calculDepense(Entretient $entretient,Assurance $assurance, Carburant $carburant): float
    {
        $totalDepense = 0.0;

        if ($this->$entretient) {
            $totalDepense += $this->$entretient->getPrix() ?? 0.0;
        }

        if ($this->$assurance) {
            $totalDepense += $this->$assurance->getPrix() ?? 0.0;
        }

        if ($this->$carburant) {
            $totalDepense += $this->$carburant->getValeur() ?? 0.0;
        }

        return $totalDepense;
    }
    public function verifierAlertes(float $depenses,float $montantAlloue) {
        if ($depenses > $montantAlloue) 
        {
            return ("Alerte: Les dépenses dépassent le montant alloué.");
        }
    }
}
