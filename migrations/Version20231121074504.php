<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231121074504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE player_country DROP FOREIGN KEY FK_3287362399E6F5DF');
        $this->addSql('ALTER TABLE player_country DROP FOREIGN KEY FK_32873623F92F3E70');
        $this->addSql('DROP TABLE player_country');
        $this->addSql('ALTER TABLE country ADD nationality_name_male VARCHAR(150) NOT NULL, ADD nationality_name_female VARCHAR(150) NOT NULL');
        $this->addSql('ALTER TABLE editor DROP FOREIGN KEY FK_CCF1F1BAF92F3E70');
        $this->addSql('DROP INDEX IDX_CCF1F1BAF92F3E70 ON editor');
        $this->addSql('ALTER TABLE editor ADD country VARCHAR(2) NOT NULL, DROP country_id');
        $this->addSql('ALTER TABLE game DROP release_date, DROP link_to_purchase');
        $this->addSql('ALTER TABLE player ADD country VARCHAR(2) NOT NULL, CHANGE earning earning NUMERIC(11, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61FF92F3E70');
        $this->addSql('DROP INDEX IDX_C4E0A61FF92F3E70 ON team');
        $this->addSql('ALTER TABLE team ADD country VARCHAR(2) NOT NULL, DROP country_id');
        $this->addSql('ALTER TABLE user ADD team_id INT DEFAULT NULL, ADD is_of_legal_age TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649296CD8AE ON user (team_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE player_country (player_id INT NOT NULL, country_id INT NOT NULL, INDEX IDX_3287362399E6F5DF (player_id), INDEX IDX_32873623F92F3E70 (country_id), PRIMARY KEY(player_id, country_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE player_country ADD CONSTRAINT FK_3287362399E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE player_country ADD CONSTRAINT FK_32873623F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('ALTER TABLE country DROP nationality_name_male, DROP nationality_name_female');
        $this->addSql('ALTER TABLE team ADD country_id INT NOT NULL, DROP country');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_C4E0A61FF92F3E70 ON team (country_id)');
        $this->addSql('ALTER TABLE editor ADD country_id INT NOT NULL, DROP country');
        $this->addSql('ALTER TABLE editor ADD CONSTRAINT FK_CCF1F1BAF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_CCF1F1BAF92F3E70 ON editor (country_id)');
        $this->addSql('ALTER TABLE game ADD release_date DATE NOT NULL, ADD link_to_purchase LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE player DROP country, CHANGE earning earning NUMERIC(11, 2) NOT NULL');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649296CD8AE');
        $this->addSql('DROP INDEX IDX_8D93D649296CD8AE ON user');
        $this->addSql('ALTER TABLE user DROP team_id, DROP is_of_legal_age');
    }
}
