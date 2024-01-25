<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231115112832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE address_id_seq CASCADE');
        $this->addSql('ALTER TABLE address DROP CONSTRAINT fk_d4e6f819395c3f3');
        $this->addSql('DROP TABLE address');
        $this->addSql('ALTER TABLE "order" ADD time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE address_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE address (id INT NOT NULL, customer_id INT DEFAULT NULL, street VARCHAR(255) NOT NULL, house_number INT NOT NULL, entrance VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_d4e6f819395c3f3 ON address (customer_id)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT fk_d4e6f819395c3f3 FOREIGN KEY (customer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" DROP time');
    }
}
