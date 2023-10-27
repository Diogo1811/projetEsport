<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231027073903 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE social_media_account DROP FOREIGN KEY FK_AA5B5E7964AE4959');
        $this->addSql('DROP TABLE social_media');
        $this->addSql('ALTER TABLE roster DROP FOREIGN KEY FK_60B9ADF9E48FD905');
        $this->addSql('DROP INDEX IDX_60B9ADF9E48FD905 ON roster');
        $this->addSql('ALTER TABLE roster DROP game_id');
        $this->addSql('DROP INDEX IDX_AA5B5E7964AE4959 ON social_media_account');
        $this->addSql('ALTER TABLE social_media_account ADD name VARCHAR(50) NOT NULL, DROP social_media_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE social_media (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE roster ADD game_id INT NOT NULL');
        $this->addSql('ALTER TABLE roster ADD CONSTRAINT FK_60B9ADF9E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_60B9ADF9E48FD905 ON roster (game_id)');
        $this->addSql('ALTER TABLE social_media_account ADD social_media_id INT NOT NULL, DROP name');
        $this->addSql('ALTER TABLE social_media_account ADD CONSTRAINT FK_AA5B5E7964AE4959 FOREIGN KEY (social_media_id) REFERENCES social_media (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_AA5B5E7964AE4959 ON social_media_account (social_media_id)');
    }
}
