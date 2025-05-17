<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250517190954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE user_detail (id SERIAL NOT NULL, user_id INT NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, birthdate VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "like" ADD liked_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post ADD title VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ALTER roles DROP DEFAULT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ALTER roles DROP NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_detail
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post DROP title
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ALTER roles SET DEFAULT '[]'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ALTER roles SET NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "like" DROP liked_at
        SQL);
    }
}
