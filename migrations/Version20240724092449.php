<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240724092449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE assurance (id INT AUTO_INCREMENT NOT NULL, numero VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, agence VARCHAR(255) NOT NULL, date DATE NOT NULL, archive TINYINT(1) NOT NULL, prix DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE budget (id INT AUTO_INCREMENT NOT NULL, montant_alloue DOUBLE PRECISION NOT NULL, depense DOUBLE PRECISION NOT NULL, archive TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE carburant (id INT AUTO_INCREMENT NOT NULL, numserie VARCHAR(255) NOT NULL, valeur DOUBLE PRECISION NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, archive TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE departement (id INT AUTO_INCREMENT NOT NULL, directeur_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, archive TINYINT(1) NOT NULL, INDEX IDX_C1765B63E82E7EE8 (directeur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entretient (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, type VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, archive TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE historique (id INT AUTO_INCREMENT NOT NULL, vehicule_id INT DEFAULT NULL, date DATE NOT NULL, description VARCHAR(255) NOT NULL, cout DOUBLE PRECISION NOT NULL, archive TINYINT(1) NOT NULL, INDEX IDX_EDBFD5EC4A4A3511 (vehicule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, departement_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, tel VARCHAR(255) DEFAULT NULL, cin VARCHAR(255) NOT NULL, role VARCHAR(20) NOT NULL, type VARCHAR(255) NOT NULL, nombre_limite_vehicules INT DEFAULT NULL, INDEX IDX_1D1C63B3CCF9E01E (departement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicule (id INT AUTO_INCREMENT NOT NULL, num_serie_carburant_id INT DEFAULT NULL, numero_assurance_id INT DEFAULT NULL, id_budget_id INT DEFAULT NULL, entretient_id INT DEFAULT NULL, departement_id INT DEFAULT NULL, responsabledeflotte_id INT DEFAULT NULL, directeur_commercial_id INT DEFAULT NULL, directeur_id INT DEFAULT NULL, marque VARCHAR(255) NOT NULL, modele VARCHAR(255) NOT NULL, annee INT NOT NULL, immatriculation VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, couleur VARCHAR(255) NOT NULL, prix_vehicule DOUBLE PRECISION NOT NULL, numero_serie VARCHAR(255) NOT NULL, kilometrage INT NOT NULL, type VARCHAR(255) NOT NULL, dimension_roue DOUBLE PRECISION NOT NULL, date_derniere_vidange DATE NOT NULL, carte_peage VARCHAR(255) NOT NULL, archived TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_292FFF1DA78068CD (num_serie_carburant_id), UNIQUE INDEX UNIQ_292FFF1DB91CA3CD (numero_assurance_id), UNIQUE INDEX UNIQ_292FFF1DB69E1A9F (id_budget_id), INDEX IDX_292FFF1D422ACA96 (entretient_id), INDEX IDX_292FFF1DCCF9E01E (departement_id), INDEX IDX_292FFF1DF7FCFB07 (responsabledeflotte_id), INDEX IDX_292FFF1DD1E5E79F (directeur_commercial_id), INDEX IDX_292FFF1DE82E7EE8 (directeur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicule_entretient (vehicule_id INT NOT NULL, entretient_id INT NOT NULL, INDEX IDX_BF58A31B4A4A3511 (vehicule_id), INDEX IDX_BF58A31B422ACA96 (entretient_id), PRIMARY KEY(vehicule_id, entretient_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE departement ADD CONSTRAINT FK_C1765B63E82E7EE8 FOREIGN KEY (directeur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE historique ADD CONSTRAINT FK_EDBFD5EC4A4A3511 FOREIGN KEY (vehicule_id) REFERENCES vehicule (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3CCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)');
        $this->addSql('ALTER TABLE vehicule ADD CONSTRAINT FK_292FFF1DA78068CD FOREIGN KEY (num_serie_carburant_id) REFERENCES carburant (id)');
        $this->addSql('ALTER TABLE vehicule ADD CONSTRAINT FK_292FFF1DB91CA3CD FOREIGN KEY (numero_assurance_id) REFERENCES assurance (id)');
        $this->addSql('ALTER TABLE vehicule ADD CONSTRAINT FK_292FFF1DB69E1A9F FOREIGN KEY (id_budget_id) REFERENCES budget (id)');
        $this->addSql('ALTER TABLE vehicule ADD CONSTRAINT FK_292FFF1D422ACA96 FOREIGN KEY (entretient_id) REFERENCES entretient (id)');
        $this->addSql('ALTER TABLE vehicule ADD CONSTRAINT FK_292FFF1DCCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)');
        $this->addSql('ALTER TABLE vehicule ADD CONSTRAINT FK_292FFF1DF7FCFB07 FOREIGN KEY (responsabledeflotte_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE vehicule ADD CONSTRAINT FK_292FFF1DD1E5E79F FOREIGN KEY (directeur_commercial_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE vehicule ADD CONSTRAINT FK_292FFF1DE82E7EE8 FOREIGN KEY (directeur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE vehicule_entretient ADD CONSTRAINT FK_BF58A31B4A4A3511 FOREIGN KEY (vehicule_id) REFERENCES vehicule (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vehicule_entretient ADD CONSTRAINT FK_BF58A31B422ACA96 FOREIGN KEY (entretient_id) REFERENCES entretient (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE departement DROP FOREIGN KEY FK_C1765B63E82E7EE8');
        $this->addSql('ALTER TABLE historique DROP FOREIGN KEY FK_EDBFD5EC4A4A3511');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3CCF9E01E');
        $this->addSql('ALTER TABLE vehicule DROP FOREIGN KEY FK_292FFF1DA78068CD');
        $this->addSql('ALTER TABLE vehicule DROP FOREIGN KEY FK_292FFF1DB91CA3CD');
        $this->addSql('ALTER TABLE vehicule DROP FOREIGN KEY FK_292FFF1DB69E1A9F');
        $this->addSql('ALTER TABLE vehicule DROP FOREIGN KEY FK_292FFF1D422ACA96');
        $this->addSql('ALTER TABLE vehicule DROP FOREIGN KEY FK_292FFF1DCCF9E01E');
        $this->addSql('ALTER TABLE vehicule DROP FOREIGN KEY FK_292FFF1DF7FCFB07');
        $this->addSql('ALTER TABLE vehicule DROP FOREIGN KEY FK_292FFF1DD1E5E79F');
        $this->addSql('ALTER TABLE vehicule DROP FOREIGN KEY FK_292FFF1DE82E7EE8');
        $this->addSql('ALTER TABLE vehicule_entretient DROP FOREIGN KEY FK_BF58A31B4A4A3511');
        $this->addSql('ALTER TABLE vehicule_entretient DROP FOREIGN KEY FK_BF58A31B422ACA96');
        $this->addSql('DROP TABLE assurance');
        $this->addSql('DROP TABLE budget');
        $this->addSql('DROP TABLE carburant');
        $this->addSql('DROP TABLE departement');
        $this->addSql('DROP TABLE entretient');
        $this->addSql('DROP TABLE historique');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE vehicule');
        $this->addSql('DROP TABLE vehicule_entretient');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
