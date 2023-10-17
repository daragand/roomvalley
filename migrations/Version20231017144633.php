<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231017144633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, address VARCHAR(255) NOT NULL, zip VARCHAR(5) NOT NULL, city VARCHAR(50) NOT NULL, floor INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipment (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, name VARCHAR(20) NOT NULL, description LONGTEXT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_D338D583C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipment_software (equipment_id INT NOT NULL, software_id INT NOT NULL, INDEX IDX_4713ECE9517FE9FE (equipment_id), INDEX IDX_4713ECE9D7452741 (software_id), PRIMARY KEY(equipment_id, software_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipment_room_quantity (id INT AUTO_INCREMENT NOT NULL, room_id INT NOT NULL, equipment_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_5F0033D054177093 (room_id), INDEX IDX_5F0033D0517FE9FE (equipment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ergonomy (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, description LONGTEXT DEFAULT NULL, icon VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images_room (id INT AUTO_INCREMENT NOT NULL, room_id INT NOT NULL, path VARCHAR(255) NOT NULL, INDEX IDX_4891606454177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, room_id INT NOT NULL, date_start DATE NOT NULL, date_end DATE NOT NULL, total_price DOUBLE PRECISION NOT NULL, is_confirmed TINYINT(1) NOT NULL, INDEX IDX_42C8495567B3B43D (users_id), INDEX IDX_42C8495554177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, address_id INT NOT NULL, status_id INT NOT NULL, name VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, capacity INT DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, INDEX IDX_729F519BF5B7AF75 (address_id), INDEX IDX_729F519B6BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_ergonomy (room_id INT NOT NULL, ergonomy_id INT NOT NULL, INDEX IDX_49537F5154177093 (room_id), INDEX IDX_49537F5145CF4AB6 (ergonomy_id), PRIMARY KEY(room_id, ergonomy_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE software (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, version VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_equipment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, icon VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, address_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(20) NOT NULL, lastname VARCHAR(20) NOT NULL, phone VARCHAR(20) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipment ADD CONSTRAINT FK_D338D583C54C8C93 FOREIGN KEY (type_id) REFERENCES type_equipment (id)');
        $this->addSql('ALTER TABLE equipment_software ADD CONSTRAINT FK_4713ECE9517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipment_software ADD CONSTRAINT FK_4713ECE9D7452741 FOREIGN KEY (software_id) REFERENCES software (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipment_room_quantity ADD CONSTRAINT FK_5F0033D054177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE equipment_room_quantity ADD CONSTRAINT FK_5F0033D0517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id)');
        $this->addSql('ALTER TABLE images_room ADD CONSTRAINT FK_4891606454177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495567B3B43D FOREIGN KEY (users_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495554177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519BF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519B6BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE room_ergonomy ADD CONSTRAINT FK_49537F5154177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE room_ergonomy ADD CONSTRAINT FK_49537F5145CF4AB6 FOREIGN KEY (ergonomy_id) REFERENCES ergonomy (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipment DROP FOREIGN KEY FK_D338D583C54C8C93');
        $this->addSql('ALTER TABLE equipment_software DROP FOREIGN KEY FK_4713ECE9517FE9FE');
        $this->addSql('ALTER TABLE equipment_software DROP FOREIGN KEY FK_4713ECE9D7452741');
        $this->addSql('ALTER TABLE equipment_room_quantity DROP FOREIGN KEY FK_5F0033D054177093');
        $this->addSql('ALTER TABLE equipment_room_quantity DROP FOREIGN KEY FK_5F0033D0517FE9FE');
        $this->addSql('ALTER TABLE images_room DROP FOREIGN KEY FK_4891606454177093');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495567B3B43D');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495554177093');
        $this->addSql('ALTER TABLE room DROP FOREIGN KEY FK_729F519BF5B7AF75');
        $this->addSql('ALTER TABLE room DROP FOREIGN KEY FK_729F519B6BF700BD');
        $this->addSql('ALTER TABLE room_ergonomy DROP FOREIGN KEY FK_49537F5154177093');
        $this->addSql('ALTER TABLE room_ergonomy DROP FOREIGN KEY FK_49537F5145CF4AB6');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649F5B7AF75');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE equipment');
        $this->addSql('DROP TABLE equipment_software');
        $this->addSql('DROP TABLE equipment_room_quantity');
        $this->addSql('DROP TABLE ergonomy');
        $this->addSql('DROP TABLE images_room');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE room_ergonomy');
        $this->addSql('DROP TABLE software');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE type_equipment');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
