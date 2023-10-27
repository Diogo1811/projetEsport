<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231027064706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, flag VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE editor (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, name VARCHAR(100) NOT NULL, link_to_official_page LONGTEXT DEFAULT NULL, INDEX IDX_CCF1F1BAF92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE encounter (id INT AUTO_INCREMENT NOT NULL, tournament_id INT NOT NULL, start_date DATETIME NOT NULL, link_to_replay LONGTEXT DEFAULT NULL, INDEX IDX_69D229CA33D1A3E7 (tournament_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE encounter_result (id INT AUTO_INCREMENT NOT NULL, encounter_id INT NOT NULL, roster_id INT DEFAULT NULL, score NUMERIC(11, 2) NOT NULL, ranking INT NOT NULL, INDEX IDX_178C261BD6E2FADC (encounter_id), INDEX IDX_178C261B75404483 (roster_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, editor_id INT NOT NULL, name VARCHAR(200) NOT NULL, release_date DATE NOT NULL, link_to_purchase LONGTEXT DEFAULT NULL, INDEX IDX_232B318C6995AC4C (editor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, last_name VARCHAR(100) NOT NULL, firstname VARCHAR(100) NOT NULL, nickname VARCHAR(100) NOT NULL, gender VARCHAR(10) NOT NULL, biography LONGTEXT DEFAULT NULL, birth_date DATE NOT NULL, earning NUMERIC(11, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player_country (player_id INT NOT NULL, country_id INT NOT NULL, INDEX IDX_3287362399E6F5DF (player_id), INDEX IDX_32873623F92F3E70 (country_id), PRIMARY KEY(player_id, country_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player_roster (id INT AUTO_INCREMENT NOT NULL, player_id INT NOT NULL, roster_id INT NOT NULL, playing_start_date DATE NOT NULL, playing_end_date DATE DEFAULT NULL, role VARCHAR(20) DEFAULT NULL, INDEX IDX_FFB93B2799E6F5DF (player_id), INDEX IDX_FFB93B2775404483 (roster_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roster (id INT AUTO_INCREMENT NOT NULL, team_id INT NOT NULL, game_id INT NOT NULL, start_date DATE NOT NULL, end_date DATE DEFAULT NULL, INDEX IDX_60B9ADF9296CD8AE (team_id), INDEX IDX_60B9ADF9E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE social_media (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE social_media_account (id INT AUTO_INCREMENT NOT NULL, social_media_id INT NOT NULL, player_id INT DEFAULT NULL, team_id INT DEFAULT NULL, editor_id INT DEFAULT NULL, game_id INT DEFAULT NULL, link_to_social_media LONGTEXT DEFAULT NULL, INDEX IDX_AA5B5E7964AE4959 (social_media_id), INDEX IDX_AA5B5E7999E6F5DF (player_id), INDEX IDX_AA5B5E79296CD8AE (team_id), INDEX IDX_AA5B5E796995AC4C (editor_id), INDEX IDX_AA5B5E79E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, name VARCHAR(50) NOT NULL, address VARCHAR(50) DEFAULT NULL, zip_code VARCHAR(15) NOT NULL, city VARCHAR(100) NOT NULL, logo LONGTEXT DEFAULT NULL, creation_date DATE NOT NULL, description LONGTEXT DEFAULT NULL, link_to_official_page LONGTEXT DEFAULT NULL, link_to_shop LONGTEXT DEFAULT NULL, earning NUMERIC(11, 2) DEFAULT NULL, INDEX IDX_C4E0A61FF92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament (id INT AUTO_INCREMENT NOT NULL, game_id INT NOT NULL, name VARCHAR(100) NOT NULL, cash_prize NUMERIC(11, 2) NOT NULL, location VARCHAR(200) DEFAULT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, INDEX IDX_BD5FB8D9E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament_result (id INT AUTO_INCREMENT NOT NULL, tournament_id INT NOT NULL, roster_id INT DEFAULT NULL, score NUMERIC(11, 2) NOT NULL, ranking INT NOT NULL, earning NUMERIC(11, 2) NOT NULL, INDEX IDX_77C03F4333D1A3E7 (tournament_id), INDEX IDX_77C03F4375404483 (roster_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE editor ADD CONSTRAINT FK_CCF1F1BAF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE encounter ADD CONSTRAINT FK_69D229CA33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
        $this->addSql('ALTER TABLE encounter_result ADD CONSTRAINT FK_178C261BD6E2FADC FOREIGN KEY (encounter_id) REFERENCES encounter (id)');
        $this->addSql('ALTER TABLE encounter_result ADD CONSTRAINT FK_178C261B75404483 FOREIGN KEY (roster_id) REFERENCES roster (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C6995AC4C FOREIGN KEY (editor_id) REFERENCES editor (id)');
        $this->addSql('ALTER TABLE player_country ADD CONSTRAINT FK_3287362399E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE player_country ADD CONSTRAINT FK_32873623F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE player_roster ADD CONSTRAINT FK_FFB93B2799E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE player_roster ADD CONSTRAINT FK_FFB93B2775404483 FOREIGN KEY (roster_id) REFERENCES roster (id)');
        $this->addSql('ALTER TABLE roster ADD CONSTRAINT FK_60B9ADF9296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE roster ADD CONSTRAINT FK_60B9ADF9E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE social_media_account ADD CONSTRAINT FK_AA5B5E7964AE4959 FOREIGN KEY (social_media_id) REFERENCES social_media (id)');
        $this->addSql('ALTER TABLE social_media_account ADD CONSTRAINT FK_AA5B5E7999E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE social_media_account ADD CONSTRAINT FK_AA5B5E79296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE social_media_account ADD CONSTRAINT FK_AA5B5E796995AC4C FOREIGN KEY (editor_id) REFERENCES editor (id)');
        $this->addSql('ALTER TABLE social_media_account ADD CONSTRAINT FK_AA5B5E79E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE tournament ADD CONSTRAINT FK_BD5FB8D9E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE tournament_result ADD CONSTRAINT FK_77C03F4333D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
        $this->addSql('ALTER TABLE tournament_result ADD CONSTRAINT FK_77C03F4375404483 FOREIGN KEY (roster_id) REFERENCES roster (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE editor DROP FOREIGN KEY FK_CCF1F1BAF92F3E70');
        $this->addSql('ALTER TABLE encounter DROP FOREIGN KEY FK_69D229CA33D1A3E7');
        $this->addSql('ALTER TABLE encounter_result DROP FOREIGN KEY FK_178C261BD6E2FADC');
        $this->addSql('ALTER TABLE encounter_result DROP FOREIGN KEY FK_178C261B75404483');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C6995AC4C');
        $this->addSql('ALTER TABLE player_country DROP FOREIGN KEY FK_3287362399E6F5DF');
        $this->addSql('ALTER TABLE player_country DROP FOREIGN KEY FK_32873623F92F3E70');
        $this->addSql('ALTER TABLE player_roster DROP FOREIGN KEY FK_FFB93B2799E6F5DF');
        $this->addSql('ALTER TABLE player_roster DROP FOREIGN KEY FK_FFB93B2775404483');
        $this->addSql('ALTER TABLE roster DROP FOREIGN KEY FK_60B9ADF9296CD8AE');
        $this->addSql('ALTER TABLE roster DROP FOREIGN KEY FK_60B9ADF9E48FD905');
        $this->addSql('ALTER TABLE social_media_account DROP FOREIGN KEY FK_AA5B5E7964AE4959');
        $this->addSql('ALTER TABLE social_media_account DROP FOREIGN KEY FK_AA5B5E7999E6F5DF');
        $this->addSql('ALTER TABLE social_media_account DROP FOREIGN KEY FK_AA5B5E79296CD8AE');
        $this->addSql('ALTER TABLE social_media_account DROP FOREIGN KEY FK_AA5B5E796995AC4C');
        $this->addSql('ALTER TABLE social_media_account DROP FOREIGN KEY FK_AA5B5E79E48FD905');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61FF92F3E70');
        $this->addSql('ALTER TABLE tournament DROP FOREIGN KEY FK_BD5FB8D9E48FD905');
        $this->addSql('ALTER TABLE tournament_result DROP FOREIGN KEY FK_77C03F4333D1A3E7');
        $this->addSql('ALTER TABLE tournament_result DROP FOREIGN KEY FK_77C03F4375404483');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE editor');
        $this->addSql('DROP TABLE encounter');
        $this->addSql('DROP TABLE encounter_result');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE player_country');
        $this->addSql('DROP TABLE player_roster');
        $this->addSql('DROP TABLE roster');
        $this->addSql('DROP TABLE social_media');
        $this->addSql('DROP TABLE social_media_account');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE tournament');
        $this->addSql('DROP TABLE tournament_result');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
