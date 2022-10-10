<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220118030604 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE score (id INT AUTO_INCREMENT NOT NULL, score VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', user_id INT DEFAULT 1, wod_id INT DEFAULT 134, skill_notes VARCHAR(500) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exercise DROP FOREIGN KEY FK_AEDAD51CC54C8C93');
        $this->addSql('CREATE TABLE wod_member_score (id INT AUTO_INCREMENT NOT NULL, score VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', user_id INT DEFAULT 1, wod_id INT DEFAULT 134, skill_notes VARCHAR(500) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE exercise_type');
        $this->addSql('DROP TABLE wod_exercise');
        $this->addSql('DROP INDEX UNIQ_AEDAD51C5E237E06 ON exercise');
        $this->addSql('DROP INDEX IDX_AEDAD51CC54C8C93 ON exercise');
        $this->addSql('ALTER TABLE exercise CHANGE type_id type_id INT DEFAULT NULL, CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE wod DROP FOREIGN KEY FK_64575EE7E3C61F9');
        $this->addSql('ALTER TABLE wod CHANGE owner_id owner_id INT DEFAULT 1, CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE wod_date_time wod_date_time DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE type type VARCHAR(45) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE notes notes VARCHAR(1000) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE wod wod VARCHAR(1000) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE UNIQUE INDEX name_UNIQUE ON wod (name)');
    }
}
