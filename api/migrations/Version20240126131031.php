<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240126131031 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vente_detail DROP FOREIGN KEY FK_211297AAD858604A');
        $this->addSql('DROP INDEX IDX_211297AAD858604A ON vente_detail');
        $this->addSql('ALTER TABLE vente_detail DROP approvisionnement_detail_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vente_detail ADD approvisionnement_detail_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vente_detail ADD CONSTRAINT FK_211297AAD858604A FOREIGN KEY (approvisionnement_detail_id) REFERENCES approvisionnement_detail (id)');
        $this->addSql('CREATE INDEX IDX_211297AAD858604A ON vente_detail (approvisionnement_detail_id)');
    }
}
