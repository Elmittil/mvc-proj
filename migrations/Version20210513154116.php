<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210513154116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, password VARCHAR(12) NOT NULL)');
        $this->addSql('DROP TABLE book');
        $this->addSql('CREATE TEMPORARY TABLE __temp__score AS SELECT id, player_name, score, game FROM score');
        $this->addSql('DROP TABLE score');
        $this->addSql('CREATE TABLE score (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, player_name VARCHAR(255) NOT NULL COLLATE BINARY, score INTEGER NOT NULL, game VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO score (id, player_name, score, game) SELECT id, player_name, score, game FROM __temp__score');
        $this->addSql('DROP TABLE __temp__score');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(100) NOT NULL COLLATE BINARY, isbn VARCHAR(25) DEFAULT NULL COLLATE BINARY, author VARCHAR(100) NOT NULL COLLATE BINARY, image VARCHAR(255) DEFAULT NULL COLLATE BINARY)');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TEMPORARY TABLE __temp__score AS SELECT id, player_name, score, game FROM score');
        $this->addSql('DROP TABLE score');
        $this->addSql('CREATE TABLE score (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, player_name VARCHAR(255) NOT NULL, score INTEGER NOT NULL, game VARCHAR(255) DEFAULT \'"game21"\' NOT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO score (id, player_name, score, game) SELECT id, player_name, score, game FROM __temp__score');
        $this->addSql('DROP TABLE __temp__score');
    }
}
