<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231110094933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_5373C9665E237E06 ON country');
        $this->addSql('ALTER TABLE country ADD nationality_name_male VARCHAR(150) NOT NULL, ADD nationality_name_female VARCHAR(150) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_CCF1F1BA5E237E06 ON editor');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE country DROP nationality_name_male, DROP nationality_name_female');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5373C9665E237E06 ON country (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CCF1F1BA5E237E06 ON editor (name)');
    }
}
