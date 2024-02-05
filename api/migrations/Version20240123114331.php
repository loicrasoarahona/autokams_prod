<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240123114331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE decaissement (id INT AUTO_INCREMENT NOT NULL, point_de_vente_id INT NOT NULL, responsable_id INT DEFAULT NULL, montant DOUBLE PRECISION NOT NULL, description LONGTEXT NOT NULL, daty DATETIME NOT NULL, INDEX IDX_E5CCA29B3F95E273 (point_de_vente_id), INDEX IDX_E5CCA29B53C59D72 (responsable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE decaisseur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE decaissement ADD CONSTRAINT FK_E5CCA29B3F95E273 FOREIGN KEY (point_de_vente_id) REFERENCES point_de_vente (id)');
        $this->addSql('ALTER TABLE decaissement ADD CONSTRAINT FK_E5CCA29B53C59D72 FOREIGN KEY (responsable_id) REFERENCES decaisseur (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE decaissement DROP FOREIGN KEY FK_E5CCA29B3F95E273');
        $this->addSql('ALTER TABLE decaissement DROP FOREIGN KEY FK_E5CCA29B53C59D72');
        $this->addSql('DROP TABLE decaissement');
        $this->addSql('DROP TABLE decaisseur');
    }
}
