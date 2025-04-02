<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250402223431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE comment (id SERIAL NOT NULL, post_id INT NOT NULL, user_id INT DEFAULT NULL, content TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9474526C4B89032C ON comment (post_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9474526CA76ED395 ON comment (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "like" (id SERIAL NOT NULL, post_id INT NOT NULL, user_id INT DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_AC6340B34B89032C ON "like" (post_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_AC6340B3A76ED395 ON "like" (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE post (id SERIAL NOT NULL, user_id INT NOT NULL, content TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5A8A6C8DA76ED395 ON post (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment ADD CONSTRAINT FK_9474526C4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "like" ADD CONSTRAINT FK_AC6340B34B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "like" ADD CONSTRAINT FK_AC6340B3A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment DROP CONSTRAINT FK_9474526C4B89032C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment DROP CONSTRAINT FK_9474526CA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "like" DROP CONSTRAINT FK_AC6340B34B89032C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "like" DROP CONSTRAINT FK_AC6340B3A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8DA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE comment
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "like"
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE post
        SQL);
    }
}
