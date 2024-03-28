<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240328104602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bike (id INT AUTO_INCREMENT NOT NULL, engine_serial VARCHAR(255) DEFAULT NULL, brand VARCHAR(50) NOT NULL, color VARCHAR(50) NOT NULL, INDEX IDX_4CBC378032573910 (engine_serial), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE engine (serial_code VARCHAR(255) NOT NULL, name VARCHAR(50) NOT NULL, horsepower INT NOT NULL, manufacturer VARCHAR(50) NOT NULL, PRIMARY KEY(serial_code)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bike ADD CONSTRAINT FK_4CBC378032573910 FOREIGN KEY (engine_serial) REFERENCES engine (serial_code)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bike DROP FOREIGN KEY FK_4CBC378032573910');
        $this->addSql('DROP TABLE bike');
        $this->addSql('DROP TABLE engine');
    }
}
