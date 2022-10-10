<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220118021328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE score (id INT AUTO_INCREMENT NOT NULL, wods_id INT NOT NULL, score VARCHAR(255) NOT NULL, INDEX IDX_32993751C1394E89 (wods_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_32993751C1394E89 FOREIGN KEY (wods_id) REFERENCES wod (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE personal_record (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE personal_recordpersonal_record (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE wod_member_score (id INT AUTO_INCREMENT NOT NULL, score VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', user_id INT DEFAULT 1, wod_id INT DEFAULT 134, skill_notes VARCHAR(500) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE score');
        $this->addSql('ALTER TABLE exercise DROP FOREIGN KEY FK_AEDAD51CC54C8C93');
        $this->addSql('DROP INDEX UNIQ_AEDAD51C5E237E06 ON exercise');
        $this->addSql('DROP INDEX IDX_AEDAD51CC54C8C93 ON exercise');
        $this->addSql('ALTER TABLE exercise CHANGE type_id type_id INT DEFAULT NULL, CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX UNIQ_D5FB359B5E237E06 ON exercise_type');
        $this->addSql('ALTER TABLE wod DROP FOREIGN KEY FK_64575EE7E3C61F9');
        $this->addSql('ALTER TABLE wod CHANGE owner_id owner_id INT DEFAULT 1, CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE wod_date_time wod_date_time DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE type type VARCHAR(45) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE notes notes VARCHAR(1000) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE wod wod VARCHAR(1000) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE UNIQUE INDEX name_UNIQUE ON wod (name)');
        $this->addSql('ALTER TABLE wod_exercise DROP FOREIGN KEY FK_5634EC3291C30030');
        $this->addSql('ALTER TABLE wod_exercise DROP FOREIGN KEY FK_5634EC32E934951A');
        $this->addSql('ALTER TABLE wod_exercise CHANGE distance distance INT DEFAULT NULL, CHANGE weight weight VARCHAR(45) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE cals cals VARCHAR(45) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE wod_exercise ADD CONSTRAINT FK_5634EC3291C30030 FOREIGN KEY (wod_id) REFERENCES wod (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wod_exercise ADD CONSTRAINT FK_5634EC32E934951A FOREIGN KEY (exercise_id) REFERENCES exercise (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
