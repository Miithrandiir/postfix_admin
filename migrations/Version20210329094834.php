<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210329094834 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mailbox (id INT AUTO_INCREMENT NOT NULL, domain_id INT NOT NULL, user_id INT DEFAULT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, maildir VARCHAR(255) NOT NULL, quota BIGINT NOT NULL, creation_date DATETIME NOT NULL, edition_date DATETIME DEFAULT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_A69FE20B115F0EE5 (domain_id), INDEX IDX_A69FE20BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mailbox ADD CONSTRAINT FK_A69FE20B115F0EE5 FOREIGN KEY (domain_id) REFERENCES domain (id)');
        $this->addSql('ALTER TABLE mailbox ADD CONSTRAINT FK_A69FE20BA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE mailbox');
    }
}
