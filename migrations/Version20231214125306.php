<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231214125306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player_country DROP FOREIGN KEY FK_3287362399E6F5DF');
        $this->addSql('ALTER TABLE player_country DROP FOREIGN KEY FK_32873623F92F3E70');
        $this->addSql('ALTER TABLE tournament_result DROP FOREIGN KEY FK_77C03F4333D1A3E7');
        $this->addSql('ALTER TABLE tournament_result DROP FOREIGN KEY FK_77C03F4375404483');
        $this->addSql('DROP TABLE player_country');
        $this->addSql('DROP TABLE tournament_result');
        $this->addSql('ALTER TABLE encounter DROP FOREIGN KEY FK_69D229CA33D1A3E7');
        $this->addSql('DROP INDEX IDX_69D229CA33D1A3E7 ON encounter');
        $this->addSql('ALTER TABLE encounter DROP tournament_id');
        $this->addSql('ALTER TABLE game ADD is_verified TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE roster ADD game_id INT NOT NULL');
        $this->addSql('ALTER TABLE roster ADD CONSTRAINT FK_60B9ADF9E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('CREATE INDEX IDX_60B9ADF9E48FD905 ON roster (game_id)');
        $this->addSql('ALTER TABLE tournament DROP cash_prize, DROP location, DROP start_date, DROP end_date, CHANGE name name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE player_country (player_id INT NOT NULL, country_id INT NOT NULL, INDEX IDX_3287362399E6F5DF (player_id), INDEX IDX_32873623F92F3E70 (country_id), PRIMARY KEY(player_id, country_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tournament_result (id INT AUTO_INCREMENT NOT NULL, tournament_id INT NOT NULL, roster_id INT DEFAULT NULL, score NUMERIC(11, 2) NOT NULL, ranking INT NOT NULL, earning NUMERIC(11, 2) NOT NULL, INDEX IDX_77C03F4333D1A3E7 (tournament_id), INDEX IDX_77C03F4375404483 (roster_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE player_country ADD CONSTRAINT FK_3287362399E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE player_country ADD CONSTRAINT FK_32873623F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tournament_result ADD CONSTRAINT FK_77C03F4333D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tournament_result ADD CONSTRAINT FK_77C03F4375404483 FOREIGN KEY (roster_id) REFERENCES roster (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE encounter ADD tournament_id INT NOT NULL');
        $this->addSql('ALTER TABLE encounter ADD CONSTRAINT FK_69D229CA33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_69D229CA33D1A3E7 ON encounter (tournament_id)');
        $this->addSql('ALTER TABLE game DROP is_verified');
        $this->addSql('ALTER TABLE roster DROP FOREIGN KEY FK_60B9ADF9E48FD905');
        $this->addSql('DROP INDEX IDX_60B9ADF9E48FD905 ON roster');
        $this->addSql('ALTER TABLE roster DROP game_id');
        $this->addSql('ALTER TABLE tournament ADD cash_prize NUMERIC(11, 2) NOT NULL, ADD location VARCHAR(200) DEFAULT NULL, ADD start_date DATETIME NOT NULL, ADD end_date DATETIME NOT NULL, CHANGE name name VARCHAR(100) NOT NULL');
    }
}
