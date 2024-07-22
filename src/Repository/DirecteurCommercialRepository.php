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

    public function genererRapportsAnalyses(DirecteurCommercial $directeurCommercial): void
    {
        // Initialize an empty array to hold report data
        $rapports = [];

        // Loop through each vehicle managed by the directeur commercial
        foreach ($this->getVehicules($directeurCommercial) as $vehicule) {
            // Fetch data related to the vehicle
            $assurance = $vehicule->getAssurance();
            $carburant = $vehicule->getCarburant();
            $budget = $vehicule->getBudget();

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
                'entretient' => $vehicule->getEntretient(),
                'dateDerniereVidange' => $vehicule->getDateDerniereVidange(),
                'cartePeage' => $vehicule->getCartePeage(),
                'assurance' => [
                    'numero' => $assurance->getNumero(),
                    'type' => $assurance->getType(),
                    'agence' => $assurance->getAgence(),
                    'date' => $assurance->getDate(),
                ],
                'budget' => [
                    'montantAlloue' => $budget->getMontantAlloue(),
                    'depenses' => $budget->getDepenses(),
                ]
            ];
        }

        // Log the generated report data
        $this->logger->info('Generated vehicle reports', ['rapports' => $rapports]);

        // Alternatively, you can save the report data to a file or a database
        // Example: saveReportToFile($reportData);
    }

    public function controlerEfficaciteGestion(DirecteurCommercial $directeurCommercial): void
    {
        // Fetch the limited number of vehicles
        $vehicules = $this->getVehicules($directeurCommercial);

        // Initialize variables to track totals
        $totalCarburantCost = 0;
        $totalBudgetCost = 0;
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
            }
        }

        // Calculate average costs
        $averageCarburantCost = $totalVehicles ? $totalCarburantCost / $totalVehicles : 0;
        $averageBudgetCost = $totalVehicles ? $totalBudgetCost / $totalVehicles : 0;

        // Log efficiency report
        $this->logger->info("Rapport sur l'efficacité du parc:");
        $this->logger->info("Total des véhicules: $totalVehicles");
        $this->logger->info("Coût total du carburant: $totalCarburantCost");
        $this->logger->info("Coût total du budget: $totalBudgetCost");
        $this->logger->info("Coût moyen du carburant par véhicule: $averageCarburantCost");
        $this->logger->info("Coût moyen du budget par véhicule: $averageBudgetCost");
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
