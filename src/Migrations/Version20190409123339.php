<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190409123339 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, departement_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, INDEX IDX_2D5B0234CCF9E01E (departement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentary (id INT AUTO_INCREMENT NOT NULL, event INT NOT NULL, user INT NOT NULL, commentaire LONGTEXT DEFAULT NULL, date_horaire DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE departement (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, INDEX IDX_C1765B6398260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(45) DEFAULT NULL, lieu VARCHAR(255) DEFAULT NULL, begin_time DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, horaire VARCHAR(255) NOT NULL, nbr_max TINYINT(1) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, private_event SMALLINT DEFAULT NULL, status INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE img (id INT AUTO_INCREMENT NOT NULL, photo VARCHAR(255) NOT NULL, id_user INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE place (id INT AUTO_INCREMENT NOT NULL, position_gps VARCHAR(255) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, ville INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE relation (id INT AUTO_INCREMENT NOT NULL, id_user2 INT DEFAULT NULL, status INT DEFAULT NULL, user_action INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test (id INT AUTO_INCREMENT NOT NULL, name INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, lastname VARCHAR(255) DEFAULT NULL, firstname VARCHAR(255) DEFAULT NULL, birthday DATE DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, number VARCHAR(255) DEFAULT NULL, gender TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B0234CCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)');
        $this->addSql('ALTER TABLE departement ADD CONSTRAINT FK_C1765B6398260155 FOREIGN KEY (region_id) REFERENCES region (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B0234CCF9E01E');
        $this->addSql('ALTER TABLE departement DROP FOREIGN KEY FK_C1765B6398260155');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE commentary');
        $this->addSql('DROP TABLE departement');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE img');
        $this->addSql('DROP TABLE place');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE relation');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE test');
        $this->addSql('DROP TABLE user');
    }
}
