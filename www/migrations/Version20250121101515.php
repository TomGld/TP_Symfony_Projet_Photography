<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250121101515 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project ADD note_id INT NOT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE26ED0855 FOREIGN KEY (note_id) REFERENCES note (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EE26ED0855 ON project (note_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE26ED0855');
        $this->addSql('DROP INDEX UNIQ_2FB3D0EE26ED0855 ON project');
        $this->addSql('ALTER TABLE project DROP note_id');
    }
}
