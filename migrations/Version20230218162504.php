<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230218162504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_user (article_id INTEGER NOT NULL, user_id INTEGER NOT NULL, PRIMARY KEY(article_id, user_id), CONSTRAINT FK_3DD151487294869C FOREIGN KEY (article_id) REFERENCES articles (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_3DD15148A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_3DD151487294869C ON article_user (article_id)');
        $this->addSql('CREATE INDEX IDX_3DD15148A76ED395 ON article_user (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__articles AS SELECT id, nom, description, pays FROM articles');
        $this->addSql('DROP TABLE articles');
        $this->addSql('CREATE TABLE articles (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type_id INTEGER NOT NULL, auteur_id INTEGER DEFAULT NULL, nom VARCHAR(150) NOT NULL, description CLOB NOT NULL, pays VARCHAR(60) DEFAULT NULL, CONSTRAINT FK_BFDD3168C54C8C93 FOREIGN KEY (type_id) REFERENCES types (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BFDD316860BB6FE6 FOREIGN KEY (auteur_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO articles (id, nom, description, pays) SELECT id, nom, description, pays FROM __temp__articles');
        $this->addSql('DROP TABLE __temp__articles');
        $this->addSql('CREATE INDEX IDX_BFDD3168C54C8C93 ON articles (type_id)');
        $this->addSql('CREATE INDEX IDX_BFDD316860BB6FE6 ON articles (auteur_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__commentaires AS SELECT id, titre, commentaire, photo FROM commentaires');
        $this->addSql('DROP TABLE commentaires');
        $this->addSql('CREATE TABLE commentaires (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, article_id INTEGER NOT NULL, auteur_id INTEGER DEFAULT NULL, titre VARCHAR(255) NOT NULL, commentaire CLOB NOT NULL, photo VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_D9BEC0C47294869C FOREIGN KEY (article_id) REFERENCES articles (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D9BEC0C460BB6FE6 FOREIGN KEY (auteur_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO commentaires (id, titre, commentaire, photo) SELECT id, titre, commentaire, photo FROM __temp__commentaires');
        $this->addSql('DROP TABLE __temp__commentaires');
        $this->addSql('CREATE INDEX IDX_D9BEC0C47294869C ON commentaires (article_id)');
        $this->addSql('CREATE INDEX IDX_D9BEC0C460BB6FE6 ON commentaires (auteur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE article_user');
        $this->addSql('CREATE TEMPORARY TABLE __temp__articles AS SELECT id, nom, description, pays FROM articles');
        $this->addSql('DROP TABLE articles');
        $this->addSql('CREATE TABLE articles (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(150) NOT NULL, description CLOB NOT NULL, pays VARCHAR(60) DEFAULT NULL)');
        $this->addSql('INSERT INTO articles (id, nom, description, pays) SELECT id, nom, description, pays FROM __temp__articles');
        $this->addSql('DROP TABLE __temp__articles');
        $this->addSql('CREATE TEMPORARY TABLE __temp__commentaires AS SELECT id, titre, commentaire, photo FROM commentaires');
        $this->addSql('DROP TABLE commentaires');
        $this->addSql('CREATE TABLE commentaires (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, commentaire CLOB NOT NULL, photo VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO commentaires (id, titre, commentaire, photo) SELECT id, titre, commentaire, photo FROM __temp__commentaires');
        $this->addSql('DROP TABLE __temp__commentaires');
    }
}
