<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220117105943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE postfix_alias (id BIGINT AUTO_INCREMENT NOT NULL, domain_id BIGINT DEFAULT NULL, adress VARCHAR(255) NOT NULL, goto VARCHAR(255) NOT NULL, date_created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_modified DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', active TINYINT(1) NOT NULL, INDEX IDX_92E82336115F0EE5 (domain_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE postfix_alias_domain (id BIGINT AUTO_INCREMENT NOT NULL, domain_origin_id BIGINT DEFAULT NULL, domain_target_id BIGINT DEFAULT NULL, date_created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_modified DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', active TINYINT(1) NOT NULL, INDEX IDX_1BFCBB49B0D2A182 (domain_origin_id), INDEX IDX_1BFCBB49F3FED928 (domain_target_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE postfix_domain (id BIGINT AUTO_INCREMENT NOT NULL, user_id BIGINT DEFAULT NULL, domain VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, nb_aliases INT NOT NULL, nb_mailboxes INT NOT NULL, maxquota INT NOT NULL, quota INT NOT NULL, backupmx TINYINT(1) NOT NULL, date_created DATETIME NOT NULL, date_modified DATETIME NOT NULL, active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_9F025887A7A91E0B (domain), INDEX IDX_9F025887A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE postfix_mailbox (id BIGINT AUTO_INCREMENT NOT NULL, domain_id BIGINT DEFAULT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) DEFAULT NULL, maildir VARCHAR(255) NOT NULL, quota BIGINT NOT NULL, date_created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_modified DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', active TINYINT(1) NOT NULL, INDEX IDX_42A98646115F0EE5 (domain_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id BIGINT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE postfix_alias ADD CONSTRAINT FK_92E82336115F0EE5 FOREIGN KEY (domain_id) REFERENCES postfix_domain (id)');
        $this->addSql('ALTER TABLE postfix_alias_domain ADD CONSTRAINT FK_1BFCBB49B0D2A182 FOREIGN KEY (domain_origin_id) REFERENCES postfix_domain (id)');
        $this->addSql('ALTER TABLE postfix_alias_domain ADD CONSTRAINT FK_1BFCBB49F3FED928 FOREIGN KEY (domain_target_id) REFERENCES postfix_domain (id)');
        $this->addSql('ALTER TABLE postfix_domain ADD CONSTRAINT FK_9F025887A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE postfix_mailbox ADD CONSTRAINT FK_42A98646115F0EE5 FOREIGN KEY (domain_id) REFERENCES postfix_domain (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE postfix_alias DROP FOREIGN KEY FK_92E82336115F0EE5');
        $this->addSql('ALTER TABLE postfix_alias_domain DROP FOREIGN KEY FK_1BFCBB49B0D2A182');
        $this->addSql('ALTER TABLE postfix_alias_domain DROP FOREIGN KEY FK_1BFCBB49F3FED928');
        $this->addSql('ALTER TABLE postfix_mailbox DROP FOREIGN KEY FK_42A98646115F0EE5');
        $this->addSql('ALTER TABLE postfix_domain DROP FOREIGN KEY FK_9F025887A76ED395');
        $this->addSql('DROP TABLE postfix_alias');
        $this->addSql('DROP TABLE postfix_alias_domain');
        $this->addSql('DROP TABLE postfix_domain');
        $this->addSql('DROP TABLE postfix_mailbox');
        $this->addSql('DROP TABLE user');
    }
}
