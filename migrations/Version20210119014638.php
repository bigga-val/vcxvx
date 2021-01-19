<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210119014638 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annee_scolaire ADD etat_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE annee_scolaire ADD CONSTRAINT FK_97150C2BD5E86FF FOREIGN KEY (etat_id) REFERENCES etat_annee (id)');
        $this->addSql('CREATE INDEX IDX_97150C2BD5E86FF ON annee_scolaire (etat_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annee_scolaire DROP FOREIGN KEY FK_97150C2BD5E86FF');
        $this->addSql('DROP INDEX IDX_97150C2BD5E86FF ON annee_scolaire');
        $this->addSql('ALTER TABLE annee_scolaire DROP etat_id');
    }
}
