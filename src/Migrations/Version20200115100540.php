<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200115100540 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE type_plat (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse LONGTEXT NOT NULL, code_postal INT NOT NULL, ville VARCHAR(255) NOT NULL, lat_long LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, emplacement LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_article (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, auteur VARCHAR(255) DEFAULT NULL, contenu LONGTEXT NOT NULL, telephone VARCHAR(255) DEFAULT NULL, mail VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, type_article_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, contenu LONGTEXT NOT NULL, INDEX IDX_23A0E666F9750B9 (type_article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_photo (article_id INT NOT NULL, photo_id INT NOT NULL, INDEX IDX_6300F6097294869C (article_id), INDEX IDX_6300F6097E9E4C8C (photo_id), PRIMARY KEY(article_id, photo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, etat_reservation VARCHAR(255) NOT NULL, etat_mail VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) NOT NULL, personnes INT NOT NULL, date DATETIME DEFAULT NULL, INDEX IDX_42C8495579F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_table (reservation_id INT NOT NULL, table_id INT NOT NULL, INDEX IDX_B5565FE1B83297E7 (reservation_id), INDEX IDX_B5565FE1ECFF285C (table_id), PRIMARY KEY(reservation_id, table_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plat (id INT AUTO_INCREMENT NOT NULL, type_plat_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_2038A207FF1CDA95 (type_plat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `table` (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, emplacement VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, taille INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E666F9750B9 FOREIGN KEY (type_article_id) REFERENCES type_article (id)');
        $this->addSql('ALTER TABLE article_photo ADD CONSTRAINT FK_6300F6097294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_photo ADD CONSTRAINT FK_6300F6097E9E4C8C FOREIGN KEY (photo_id) REFERENCES photo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495579F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation_table ADD CONSTRAINT FK_B5565FE1B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_table ADD CONSTRAINT FK_B5565FE1ECFF285C FOREIGN KEY (table_id) REFERENCES `table` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plat ADD CONSTRAINT FK_2038A207FF1CDA95 FOREIGN KEY (type_plat_id) REFERENCES type_plat (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE plat DROP FOREIGN KEY FK_2038A207FF1CDA95');
        $this->addSql('ALTER TABLE article_photo DROP FOREIGN KEY FK_6300F6097E9E4C8C');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E666F9750B9');
        $this->addSql('ALTER TABLE article_photo DROP FOREIGN KEY FK_6300F6097294869C');
        $this->addSql('ALTER TABLE reservation_table DROP FOREIGN KEY FK_B5565FE1B83297E7');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495579F37AE5');
        $this->addSql('ALTER TABLE reservation_table DROP FOREIGN KEY FK_B5565FE1ECFF285C');
        $this->addSql('DROP TABLE type_plat');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE type_article');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_photo');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE reservation_table');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE plat');
        $this->addSql('DROP TABLE `table`');
    }
}
