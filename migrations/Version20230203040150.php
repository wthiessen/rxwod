<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230203040150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
//        $this->addSql('CREATE TABLE exercise_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D5FB359B5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lift_record (id INT AUTO_INCREMENT NOT NULL, exercise VARCHAR(255) NOT NULL, rep_scheme VARCHAR(255) NOT NULL, weight INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', comment VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
//        $this->addSql('DROP TABLE article');
//        $this->addSql('DROP TABLE lift');
//        $this->addSql('DROP TABLE score');
//        $this->addSql('DROP TABLE wod_member_score');
//        $this->addSql('ALTER TABLE exercise CHANGE name name VARCHAR(255) NOT NULL, CHANGE type_id type_id INT NOT NULL');
//        $this->addSql('ALTER TABLE exercise ADD CONSTRAINT FK_AEDAD51CC54C8C93 FOREIGN KEY (type_id) REFERENCES exercise_type (id)');
//        $this->addSql('CREATE UNIQUE INDEX UNIQ_AEDAD51C5E237E06 ON exercise (name)');
//        $this->addSql('CREATE INDEX IDX_AEDAD51CC54C8C93 ON exercise (type_id)');
//        $this->addSql('ALTER TABLE leaderboard CHANGE name name VARCHAR(255) NOT NULL, CHANGE date_created date_created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE score score VARCHAR(255) NOT NULL, CHANGE rx rx INT NOT NULL, CHANGE wod wod INT NOT NULL, CHANGE comments comments VARCHAR(255) NOT NULL');
//        $this->addSql('DROP INDEX name_UNIQUE ON wod');
//        $this->addSql('ALTER TABLE wod CHANGE name name VARCHAR(255) NOT NULL, CHANGE wod wod VARCHAR(1000) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exercise DROP FOREIGN KEY FK_AEDAD51CC54C8C93');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE lift (id INT AUTO_INCREMENT NOT NULL, exercise VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, rep_scheme VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, weight INT NOT NULL, created_at DATETIME NOT NULL, comment VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE score (id INT AUTO_INCREMENT NOT NULL, wod INT NOT NULL, user INT NOT NULL, score VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE wod_member_score (id INT AUTO_INCREMENT NOT NULL, score VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', user_id INT DEFAULT 1, wod_id INT DEFAULT 134, skill_notes VARCHAR(500) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE exercise_type');
        $this->addSql('DROP TABLE lift_record');
        $this->addSql('DROP INDEX UNIQ_AEDAD51C5E237E06 ON exercise');
        $this->addSql('DROP INDEX IDX_AEDAD51CC54C8C93 ON exercise');
        $this->addSql('ALTER TABLE exercise CHANGE type_id type_id INT DEFAULT NULL, CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE leaderboard CHANGE name name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE score score VARCHAR(45) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE wod wod INT DEFAULT NULL, CHANGE date_created date_created DATETIME NOT NULL, CHANGE comments comments VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE rx rx INT DEFAULT NULL');
        $this->addSql('ALTER TABLE wod CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE wod wod VARCHAR(1000) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE UNIQUE INDEX name_UNIQUE ON wod (name)');
    }
}
