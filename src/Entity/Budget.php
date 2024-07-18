<?php

namespace App\Entity;

use App\Repository\BudgetRepository;
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
    public function CalculeDepenseRestante(float $MontantAlloue,float $depense):float{
       return ($MontantAlloue + $depense);
    }
}
