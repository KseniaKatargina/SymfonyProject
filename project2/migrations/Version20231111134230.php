<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231111134230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE order_item_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE order_item (id INT NOT NULL, user_order_id INT DEFAULT NULL, menu_item_id INT DEFAULT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_52EA1F096D128938 ON order_item (user_order_id)');
        $this->addSql('CREATE INDEX IDX_52EA1F099AB44FE0 ON order_item (menu_item_id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F096D128938 FOREIGN KEY (user_order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F099AB44FE0 FOREIGN KEY (menu_item_id) REFERENCES menu_item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE order_item_id_seq CASCADE');
        $this->addSql('ALTER TABLE order_item DROP CONSTRAINT FK_52EA1F096D128938');
        $this->addSql('ALTER TABLE order_item DROP CONSTRAINT FK_52EA1F099AB44FE0');
        $this->addSql('DROP TABLE order_item');
    }
}
