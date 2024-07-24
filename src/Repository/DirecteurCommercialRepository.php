<?php

namespace App\Repository;

use App\Entity\DirecteurCommercial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<DirecteurCommercial>
 */
class DirecteurCommercialRepository extends ServiceEntityRepository
{
    private $vehicules;
    private $logger;

    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        $this->vehicules = new ArrayCollection();
        $this->logger = $logger;
        parent::__construct($registry, DirecteurCommercial::class);
    }

    public function getVehicules(DirecteurCommercial $directeurCommercial): Collection
    {
        $limit = $directeurCommercial->getNombreLimiteVehicules();
        return new ArrayCollection($this->vehicules->slice(0, $limit));
    }

    public function genererRapportsAnalyses(DirecteurCommercial $directeurCommercial): array
    {
        // Initialize an empty array to hold report data
        $rapports = [];

        // Loop through each vehicle managed by the directeur commercial
        foreach ($this->getVehicules($directeurCommercial) as $vehicule) {
            // Fetch data related to the vehicle
            $assurance = $vehicule->getAssurance();
            $carburant = $vehicule->getCarburant();
            $budget = $vehicule->getBudget();
            $entretients = $vehicule->getEntretient();

            $entretientData = [];
            foreach ($entretients as $entretient) {
                $entretientData[] = [
                    'id' => $entretient->getId(),
                    'date' => $entretient->getDate()->format('Y-m-d'),
                    'type' => $entretient->getType(),
                    'prix' => $entretient->getPrix(),
                ];
            }

            // Analyze data and prepare a summary
            $rapports[] = [
                'id' => $vehicule->getId(),
                'marque' => $vehicule->getMarque(),
                'modele' => $vehicule->getModele(),
                'annee' => $vehicule->getAnnee(),
                'immatriculation' => $vehicule->getImmatriculation(),
                'carburant' => [
                    'numSerie' => $carburant->getNumSerie(),
                    'valeur' => $carburant->getValeur(),
                    'motDePasse' => $carburant->getMotDePasse(),
                ],
                'etat' => $vehicule->getEtat(),
                'description' => $vehicule->getDescription(),
                'couleur' => $vehicule->getCouleur(),
                'prix' => $vehicule->getPrix(),
                'numeroDeSerie' => $vehicule->getNumeroDeSerie(),
                'kilometrage' => $vehicule->getKilometrage(),
                'type' => $vehicule->getType(),
                'dimensionRoue' => $vehicule->getDimensionRoue(),
                'entretient' => $entretientData,
                'dateDerniereVidange' => $vehicule->getDateDerniereVidange(),
                'cartePeage' => $vehicule->getCartePeage(),
                'assurance' => [
                    'numero' => $assurance->getNumero(),
                    'type' => $assurance->getType(),
                    'agence' => $assurance->getAgence(),
                    'date' => $assurance->getDate(),
                    'prix' => $assurance->getPrix(),
                ],
                'budget' => [
                    'montantAlloue' => $budget->getMontantAlloue(),
                    'depenses' => $budget->getDepenses(),
                ]
            ];
        }

        // Log the generated report data
        $this->logger->info('Generated vehicle reports', ['rapports' => $rapports]);

        // Return the generated report data
        return $rapports;
    }


    public function controlerEfficaciteGestion(DirecteurCommercial $directeurCommercial): void
    {
        // Fetch the limited number of vehicles
        $vehicules = $this->getVehicules($directeurCommercial);

        // Initialize variables to track totals
        $totalCarburantCost = 0;
        $totalBudgetCost = 0;
        $totalEntretientCost = 0;
        $totalVehicles = count($vehicules);

        // Process each vehicle
        foreach ($vehicules as $vehicule) {
            $historiques = $vehicule->getHistoriques();

            foreach ($historiques as $historique) {
                if (strpos(strtolower($historique->getDescription()), 'carburant') !== false) {
                    $totalCarburantCost += $historique->getCout();
                } elseif (strpos(strtolower($historique->getDescription()), 'budget') !== false) {
                    $totalBudgetCost += $historique->getCout();
                }

                $entretients = $vehicule->getEntretient(); // Assuming this returns a collection
                foreach ($entretients as $entretient) {
                    $totalEntretientCost += $entretient->getPrix();
                }
            }
        }

        // Calculate average costs
        $averageCarburantCost = $totalVehicles ? $totalCarburantCost / $totalVehicles : 0;
        $averageBudgetCost = $totalVehicles ? $totalBudgetCost / $totalVehicles : 0;
        $averageEntretientCost = $totalVehicles ? $totalEntretientCost / $totalVehicles : 0;

        // Log efficiency report
        $this->logger->info("Rapport sur l'efficacité de le Parc:");
        $this->logger->info("Total des véhicules: $totalVehicles");
        $this->logger->info("Coût total du carburant: $totalCarburantCost");
        $this->logger->info("Coût total du Budget: $totalBudgetCost");
        $this->logger->info("Coût total de l'entretien: $totalEntretientCost");
        $this->logger->info("Coût moyen du carburant par véhicule: $averageCarburantCost");
        $this->logger->info("Coût moyen du Budget par véhicule: $averageBudgetCost");
        $this->logger->info("Coût moyen de l'entretien par véhicule: $averageEntretientCost");
    }



    //    /**
    //     * @return DirecteurCommercial[] Returns an array of DirecteurCommercial objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?DirecteurCommercial
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
