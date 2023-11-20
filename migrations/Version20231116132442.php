<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231116132442 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE editor DROP FOREIGN KEY FK_CCF1F1BAF92F3E70');
        $this->addSql('DROP INDEX IDX_CCF1F1BAF92F3E70 ON editor');
        $this->addSql('ALTER TABLE editor ADD country VARCHAR(5) NOT NULL, DROP country_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE editor ADD country_id INT NOT NULL, DROP country');
        $this->addSql('ALTER TABLE editor ADD CONSTRAINT FK_CCF1F1BAF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_CCF1F1BAF92F3E70 ON editor (country_id)');
    }
}
