<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211021094018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, birthday DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, category_id_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_169E6FB99777D11E (category_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course_class (id INT AUTO_INCREMENT NOT NULL, teacher_id_id INT NOT NULL, course_id_id INT NOT NULL, name VARCHAR(255) NOT NULL, date_start DATE NOT NULL, date_end DATE NOT NULL, INDEX IDX_4E01E772EBB220A (teacher_id_id), INDEX IDX_4E01E7796EF99BF (course_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course_class_student (course_class_id INT NOT NULL, student_id INT NOT NULL, INDEX IDX_B1DB8C1643B46646 (course_class_id), INDEX IDX_B1DB8C16CB944F1A (student_id), PRIMARY KEY(course_class_id, student_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, birthday DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teacher (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, birthday DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB99777D11E FOREIGN KEY (category_id_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE course_class ADD CONSTRAINT FK_4E01E772EBB220A FOREIGN KEY (teacher_id_id) REFERENCES teacher (id)');
        $this->addSql('ALTER TABLE course_class ADD CONSTRAINT FK_4E01E7796EF99BF FOREIGN KEY (course_id_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE course_class_student ADD CONSTRAINT FK_B1DB8C1643B46646 FOREIGN KEY (course_class_id) REFERENCES course_class (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course_class_student ADD CONSTRAINT FK_B1DB8C16CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB99777D11E');
        $this->addSql('ALTER TABLE course_class DROP FOREIGN KEY FK_4E01E7796EF99BF');
        $this->addSql('ALTER TABLE course_class_student DROP FOREIGN KEY FK_B1DB8C1643B46646');
        $this->addSql('ALTER TABLE course_class_student DROP FOREIGN KEY FK_B1DB8C16CB944F1A');
        $this->addSql('ALTER TABLE course_class DROP FOREIGN KEY FK_4E01E772EBB220A');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE course_class');
        $this->addSql('DROP TABLE course_class_student');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE teacher');
    }
}
