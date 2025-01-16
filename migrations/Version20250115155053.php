<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250115155053 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appointment (id INT AUTO_INCREMENT NOT NULL, healthcare_center_id INT NOT NULL, doctor_id INT DEFAULT NULL, skill_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, start_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', message LONGTEXT DEFAULT NULL, INDEX IDX_FE38F844BD872B94 (healthcare_center_id), INDEX IDX_FE38F84487F4FB17 (doctor_id), INDEX IDX_FE38F8445585C142 (skill_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE doctor (id INT AUTO_INCREMENT NOT NULL, healthcare_center_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, photo VARCHAR(255) DEFAULT NULL, INDEX IDX_1FC0F36ABD872B94 (healthcare_center_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE doctor_skill (doctor_id INT NOT NULL, skill_id INT NOT NULL, INDEX IDX_13C9041187F4FB17 (doctor_id), INDEX IDX_13C904115585C142 (skill_id), PRIMARY KEY(doctor_id, skill_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE healthcare_center (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, phone_emergency VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE opening_hour (id INT AUTO_INCREMENT NOT NULL, healthcare_center_id INT NOT NULL, weekday VARCHAR(255) NOT NULL, weekday_number SMALLINT NOT NULL, opening_time TIME DEFAULT NULL, closing_time TIME DEFAULT NULL, INDEX IDX_969BD765BD872B94 (healthcare_center_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skill (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844BD872B94 FOREIGN KEY (healthcare_center_id) REFERENCES healthcare_center (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F84487F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F8445585C142 FOREIGN KEY (skill_id) REFERENCES skill (id)');
        $this->addSql('ALTER TABLE doctor ADD CONSTRAINT FK_1FC0F36ABD872B94 FOREIGN KEY (healthcare_center_id) REFERENCES healthcare_center (id)');
        $this->addSql('ALTER TABLE doctor_skill ADD CONSTRAINT FK_13C9041187F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE doctor_skill ADD CONSTRAINT FK_13C904115585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE opening_hour ADD CONSTRAINT FK_969BD765BD872B94 FOREIGN KEY (healthcare_center_id) REFERENCES healthcare_center (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844BD872B94');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F84487F4FB17');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F8445585C142');
        $this->addSql('ALTER TABLE doctor DROP FOREIGN KEY FK_1FC0F36ABD872B94');
        $this->addSql('ALTER TABLE doctor_skill DROP FOREIGN KEY FK_13C9041187F4FB17');
        $this->addSql('ALTER TABLE doctor_skill DROP FOREIGN KEY FK_13C904115585C142');
        $this->addSql('ALTER TABLE opening_hour DROP FOREIGN KEY FK_969BD765BD872B94');
        $this->addSql('DROP TABLE appointment');
        $this->addSql('DROP TABLE doctor');
        $this->addSql('DROP TABLE doctor_skill');
        $this->addSql('DROP TABLE healthcare_center');
        $this->addSql('DROP TABLE opening_hour');
        $this->addSql('DROP TABLE skill');
    }
}
