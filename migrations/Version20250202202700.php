<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250202202700 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE healthcare_center DROP FOREIGN KEY FK_831E3C75783E3463');
        $this->addSql('DROP INDEX IDX_831E3C75783E3463 ON healthcare_center');
        $this->addSql('ALTER TABLE healthcare_center DROP manager_id');
        $this->addSql('ALTER TABLE user ADD healthcare_center_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649BD872B94 FOREIGN KEY (healthcare_center_id) REFERENCES healthcare_center (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649BD872B94 ON user (healthcare_center_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE healthcare_center ADD manager_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE healthcare_center ADD CONSTRAINT FK_831E3C75783E3463 FOREIGN KEY (manager_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_831E3C75783E3463 ON healthcare_center (manager_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649BD872B94');
        $this->addSql('DROP INDEX IDX_8D93D649BD872B94 ON user');
        $this->addSql('ALTER TABLE user DROP healthcare_center_id');
    }
}
