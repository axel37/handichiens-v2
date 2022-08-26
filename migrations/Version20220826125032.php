<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220826125032 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE affectation (id INT AUTO_INCREMENT NOT NULL, chien_id INT NOT NULL, famille_id INT NOT NULL, debut DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', fin DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', est_confirme TINYINT(1) NOT NULL, INDEX IDX_F4DD61D3BFCF400E (chien_id), INDEX IDX_F4DD61D397A77B84 (famille_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chien (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, date_naissance DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', race VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE disponibilite (id INT AUTO_INCREMENT NOT NULL, famille_id INT NOT NULL, debut DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', fin DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', libelle VARCHAR(255) DEFAULT NULL, INDEX IDX_2CBACE2F97A77B84 (famille_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE famille (id INT NOT NULL, adresse VARCHAR(255) NOT NULL, ville VARCHAR(125) NOT NULL, code_postal VARCHAR(5) NOT NULL, commentaire LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, telephone VARCHAR(25) DEFAULT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D3BFCF400E FOREIGN KEY (chien_id) REFERENCES chien (id)');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D397A77B84 FOREIGN KEY (famille_id) REFERENCES famille (id)');
        $this->addSql('ALTER TABLE disponibilite ADD CONSTRAINT FK_2CBACE2F97A77B84 FOREIGN KEY (famille_id) REFERENCES famille (id)');
        $this->addSql('ALTER TABLE famille ADD CONSTRAINT FK_2473F213BF396750 FOREIGN KEY (id) REFERENCES utilisateur (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE affectation DROP FOREIGN KEY FK_F4DD61D3BFCF400E');
        $this->addSql('ALTER TABLE affectation DROP FOREIGN KEY FK_F4DD61D397A77B84');
        $this->addSql('ALTER TABLE disponibilite DROP FOREIGN KEY FK_2CBACE2F97A77B84');
        $this->addSql('ALTER TABLE famille DROP FOREIGN KEY FK_2473F213BF396750');
        $this->addSql('DROP TABLE affectation');
        $this->addSql('DROP TABLE chien');
        $this->addSql('DROP TABLE disponibilite');
        $this->addSql('DROP TABLE famille');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
