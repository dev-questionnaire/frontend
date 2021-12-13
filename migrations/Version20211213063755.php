<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211213063755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE exam_question (id INT AUTO_INCREMENT NOT NULL, exam_id INT NOT NULL, question VARCHAR(255) NOT NULL, correct TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_F593067D578D5E91 (exam_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exam_question ADD CONSTRAINT FK_F593067D578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id)');
        $this->addSql('DROP TABLE question');
        $this->addSql('ALTER TABLE user_exam DROP FOREIGN KEY FK_423AEA0F578D5E91');
        $this->addSql('DROP INDEX IDX_423AEA0F578D5E91 ON user_exam');
        $this->addSql('ALTER TABLE user_exam ADD answer TINYINT(1) DEFAULT NULL, DROP questions, CHANGE exam_id exam_question_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_exam ADD CONSTRAINT FK_423AEA0F5345BBE FOREIGN KEY (exam_question_id) REFERENCES exam_question (id)');
        $this->addSql('CREATE INDEX IDX_423AEA0F5345BBE ON user_exam (exam_question_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_exam DROP FOREIGN KEY FK_423AEA0F5345BBE');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, exam_id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, answers JSON NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_B6F7494E578D5E91 (exam_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE exam_question');
        $this->addSql('DROP INDEX IDX_423AEA0F5345BBE ON user_exam');
        $this->addSql('ALTER TABLE user_exam ADD questions JSON NOT NULL, DROP answer, CHANGE exam_question_id exam_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_exam ADD CONSTRAINT FK_423AEA0F578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_423AEA0F578D5E91 ON user_exam (exam_id)');
    }
}
