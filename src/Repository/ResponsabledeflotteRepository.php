<?php

namespace App\Repository;

use App\Entity\Vehicule;
use App\Repository\VehiculeRepository;
use Psr\Log\LoggerInterface;
use App\Entity\Responsabledeflotte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Responsabledeflotte>
 */
class ResponsabledeflotteRepository extends ServiceEntityRepository
{
    private $vehicleRepository;
    private $logger;

    public function __construct(ManagerRegistry $registry, VehiculeRepository $vehicleRepository, LoggerInterface $logger)
    {
        $this->vehicleRepository = $vehicleRepository;
        $this->logger = $logger;
        parent::__construct($registry, Responsabledeflotte::class);
    }

    public function suivreHistoriqueVehicule(Vehicule $vehicle): array
    {
        // Example logic to retrieve vehicle history
        $vehicleHistoryRepository = $this->entityManager->getRepository('App:Historique');
        $history = $vehicleHistoryRepository->findBy(['vehicle' => $vehicle], ['date' => 'DESC']);

        // Log the retrieval of history
        $this->logger->info('Vehicle history retrieved.', [
            'vehicle_id' => $vehicle->getId(),
            'history_count' => count($history),
        ]);

        return $history;
    }

    public function gererBudgetVehicule(Vehicule $vehicle, float $expense): void
    {
        // Assuming the Vehicle entity has a method to get the associated Budget entity
        $budget = $vehicle->getIdBudget();

        if ($budget !== null) {
            $currentDepenses = $budget->getDepense();
            $budget->setDepense($currentDepenses + $expense);

            // Log the expense
            $this->logger->info("Expense of $expense applied to vehicle with ID {$vehicle->getId()}.");

            // Persist changes to the database
            $this->vehicleRepository->save($vehicle);
        } else {
            // Handle the case where the vehicle does not have an associated budget
            $this->logger->warning("No budget found for vehicle with ID {$vehicle->getId()}.");
        }
    }


    //    /**
    //     * @return Responsabledeflotte[] Returns an array of Responsabledeflotte objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Responsabledeflotte
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
