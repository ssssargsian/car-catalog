<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241106061710 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE models (id UUID NOT NULL, brand_id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E4D6300944F5D008 ON models (brand_id)');
        $this->addSql('COMMENT ON COLUMN models.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN models.brand_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE specifications (id UUID NOT NULL, model_id UUID NOT NULL, fuel_type VARCHAR(100) NOT NULL, engine_volume DOUBLE PRECISION NOT NULL, power INT NOT NULL, fuel_tank_capacity INT NOT NULL, drive_type VARCHAR(50) NOT NULL, body_type VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BDA8A4B7975B7E7 ON specifications (model_id)');
        $this->addSql('COMMENT ON COLUMN specifications.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN specifications.model_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE models ADD CONSTRAINT FK_E4D6300944F5D008 FOREIGN KEY (brand_id) REFERENCES brands (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE specifications ADD CONSTRAINT FK_BDA8A4B7975B7E7 FOREIGN KEY (model_id) REFERENCES models (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE models DROP CONSTRAINT FK_E4D6300944F5D008');
        $this->addSql('ALTER TABLE specifications DROP CONSTRAINT FK_BDA8A4B7975B7E7');
        $this->addSql('DROP TABLE models');
        $this->addSql('DROP TABLE specifications');
    }
}
