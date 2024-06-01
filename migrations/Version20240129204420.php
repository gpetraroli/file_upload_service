<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240129204420 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE uploaded_file DROP FOREIGN KEY FK_B40DF75D7987212D');
        $this->addSql('CREATE TABLE container (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C7A2EC1BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE container ADD CONSTRAINT FK_C7A2EC1BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE app DROP FOREIGN KEY FK_C96E70CFA76ED395');
        $this->addSql('DROP TABLE app');
        $this->addSql('DROP INDEX IDX_B40DF75D7987212D ON uploaded_file');
        $this->addSql('ALTER TABLE uploaded_file CHANGE app_id container_id INT NOT NULL');
        $this->addSql('ALTER TABLE uploaded_file ADD CONSTRAINT FK_B40DF75DBC21F742 FOREIGN KEY (container_id) REFERENCES container (id)');
        $this->addSql('CREATE INDEX IDX_B40DF75DBC21F742 ON uploaded_file (container_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE uploaded_file DROP FOREIGN KEY FK_B40DF75DBC21F742');
        $this->addSql('CREATE TABLE app (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C96E70CFA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE app ADD CONSTRAINT FK_C96E70CFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE container DROP FOREIGN KEY FK_C7A2EC1BA76ED395');
        $this->addSql('DROP TABLE container');
        $this->addSql('DROP INDEX IDX_B40DF75DBC21F742 ON uploaded_file');
        $this->addSql('ALTER TABLE uploaded_file CHANGE container_id app_id INT NOT NULL');
        $this->addSql('ALTER TABLE uploaded_file ADD CONSTRAINT FK_B40DF75D7987212D FOREIGN KEY (app_id) REFERENCES app (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_B40DF75D7987212D ON uploaded_file (app_id)');
    }
}
