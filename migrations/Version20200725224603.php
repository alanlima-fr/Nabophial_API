<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200725224603 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE relation (first_user_id INT NOT NULL, second_user_id INT NOT NULL, relation_status_id INT DEFAULT NULL, initiator INT DEFAULT NULL, INDEX IDX_62894749B4E2BF69 (first_user_id), INDEX IDX_62894749B02C53F8 (second_user_id), INDEX IDX_62894749AE6A2039 (relation_status_id), PRIMARY KEY(first_user_id, second_user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, begin_at DATETIME NOT NULL, end_at DATETIME NOT NULL, max_participant INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, private_event TINYINT(1) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE place (id INT AUTO_INCREMENT NOT NULL, position_gps VARCHAR(255) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, ville INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE img (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, INDEX IDX_BBC2C8ACA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_performance (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sport (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE relation_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentary (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, user_id INT DEFAULT NULL, payload LONGTEXT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_1CAC12CA71F7E88B (event_id), INDEX IDX_1CAC12CAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_user (id INT AUTO_INCREMENT NOT NULL, password VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, birthday DATE NOT NULL, email VARCHAR(255) NOT NULL, number VARCHAR(10) DEFAULT NULL, male TINYINT(1) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_88BDF3E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_user_sport (app_user_id INT NOT NULL, sport_id INT NOT NULL, INDEX IDX_AE5CBFF64A3353D8 (app_user_id), INDEX IDX_AE5CBFF6AC78BCF8 (sport_id), PRIMARY KEY(app_user_id, sport_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE performance (id INT AUTO_INCREMENT NOT NULL, type_performance_id INT DEFAULT NULL, sport_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, INDEX IDX_82D79681EFD9F77A (type_performance_id), INDEX IDX_82D79681AC78BCF8 (sport_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_62894749B4E2BF69 FOREIGN KEY (first_user_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_62894749B02C53F8 FOREIGN KEY (second_user_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_62894749AE6A2039 FOREIGN KEY (relation_status_id) REFERENCES relation_status (id)');
        $this->addSql('ALTER TABLE img ADD CONSTRAINT FK_BBC2C8ACA76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE commentary ADD CONSTRAINT FK_1CAC12CA71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE commentary ADD CONSTRAINT FK_1CAC12CAA76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE app_user_sport ADD CONSTRAINT FK_AE5CBFF64A3353D8 FOREIGN KEY (app_user_id) REFERENCES app_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_user_sport ADD CONSTRAINT FK_AE5CBFF6AC78BCF8 FOREIGN KEY (sport_id) REFERENCES sport (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE performance ADD CONSTRAINT FK_82D79681EFD9F77A FOREIGN KEY (type_performance_id) REFERENCES type_performance (id)');
        $this->addSql('ALTER TABLE performance ADD CONSTRAINT FK_82D79681AC78BCF8 FOREIGN KEY (sport_id) REFERENCES sport (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commentary DROP FOREIGN KEY FK_1CAC12CA71F7E88B');
        $this->addSql('ALTER TABLE performance DROP FOREIGN KEY FK_82D79681EFD9F77A');
        $this->addSql('ALTER TABLE app_user_sport DROP FOREIGN KEY FK_AE5CBFF6AC78BCF8');
        $this->addSql('ALTER TABLE performance DROP FOREIGN KEY FK_82D79681AC78BCF8');
        $this->addSql('ALTER TABLE relation DROP FOREIGN KEY FK_62894749AE6A2039');
        $this->addSql('ALTER TABLE relation DROP FOREIGN KEY FK_62894749B4E2BF69');
        $this->addSql('ALTER TABLE relation DROP FOREIGN KEY FK_62894749B02C53F8');
        $this->addSql('ALTER TABLE img DROP FOREIGN KEY FK_BBC2C8ACA76ED395');
        $this->addSql('ALTER TABLE commentary DROP FOREIGN KEY FK_1CAC12CAA76ED395');
        $this->addSql('ALTER TABLE app_user_sport DROP FOREIGN KEY FK_AE5CBFF64A3353D8');
        $this->addSql('DROP TABLE relation');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE place');
        $this->addSql('DROP TABLE img');
        $this->addSql('DROP TABLE type_performance');
        $this->addSql('DROP TABLE sport');
        $this->addSql('DROP TABLE relation_status');
        $this->addSql('DROP TABLE commentary');
        $this->addSql('DROP TABLE app_user');
        $this->addSql('DROP TABLE app_user_sport');
        $this->addSql('DROP TABLE performance');
    }
}
