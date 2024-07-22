<?php

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateur;

class UserService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(Utilisateur $utilisateur): void
    {
        $this->entityManager->persist($utilisateur);
        $this->entityManager->flush();
    }

    public function delete(Utilisateur $utilisateur): void
    {
        $this->entityManager->remove($utilisateur);
        $this->entityManager->flush();
    }
}
