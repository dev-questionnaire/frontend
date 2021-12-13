<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211208142852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_exam (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, exam_id INT NOT NULL, questions JSON NOT NULL, INDEX IDX_423AEA0FA76ED395 (user_id), INDEX IDX_423AEA0F578D5E91 (exam_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_exam ADD CONSTRAINT FK_423AEA0FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_exam ADD CONSTRAINT FK_423AEA0F578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_exam');
    }
}
