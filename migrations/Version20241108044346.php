<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241108044346 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE models DROP CONSTRAINT FK_E4D6300944F5D008');
        $this->addSql('ALTER TABLE models ADD CONSTRAINT FK_E4D6300944F5D008 FOREIGN KEY (brand_id) REFERENCES brands (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE specifications DROP CONSTRAINT FK_BDA8A4B7975B7E7');
        $this->addSql('ALTER TABLE specifications ADD CONSTRAINT FK_BDA8A4B7975B7E7 FOREIGN KEY (model_id) REFERENCES models (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE models DROP CONSTRAINT fk_e4d6300944f5d008');
        $this->addSql('ALTER TABLE models ADD CONSTRAINT fk_e4d6300944f5d008 FOREIGN KEY (brand_id) REFERENCES brands (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE specifications DROP CONSTRAINT fk_bda8a4b7975b7e7');
        $this->addSql('ALTER TABLE specifications ADD CONSTRAINT fk_bda8a4b7975b7e7 FOREIGN KEY (model_id) REFERENCES models (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
