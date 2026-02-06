<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260206112740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY `FK_5F9E962A63379586`');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY `FK_5F9E962A67B3B43D`');
        $this->addSql('DROP INDEX IDX_5F9E962A63379586 ON comments');
        $this->addSql('DROP INDEX IDX_5F9E962A67B3B43D ON comments');
        $this->addSql('ALTER TABLE comments CHANGE comments_id parent_id INT DEFAULT NULL, CHANGE users_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A727ACA70 FOREIGN KEY (parent_id) REFERENCES comments (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5F9E962A727ACA70 ON comments (parent_id)');
        $this->addSql('CREATE INDEX IDX_5F9E962AA76ED395 ON comments (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A727ACA70');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AA76ED395');
        $this->addSql('DROP INDEX IDX_5F9E962A727ACA70 ON comments');
        $this->addSql('DROP INDEX IDX_5F9E962AA76ED395 ON comments');
        $this->addSql('ALTER TABLE comments CHANGE parent_id comments_id INT DEFAULT NULL, CHANGE user_id users_id INT NOT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT `FK_5F9E962A63379586` FOREIGN KEY (comments_id) REFERENCES comments (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT `FK_5F9E962A67B3B43D` FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5F9E962A63379586 ON comments (comments_id)');
        $this->addSql('CREATE INDEX IDX_5F9E962A67B3B43D ON comments (users_id)');
    }
}
