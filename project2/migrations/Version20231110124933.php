<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231110124933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "order_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "order" (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE order_menu_item (order_id INT NOT NULL, menu_item_id INT NOT NULL, PRIMARY KEY(order_id, menu_item_id))');
        $this->addSql('CREATE INDEX IDX_6BD3AEA88D9F6D38 ON order_menu_item (order_id)');
        $this->addSql('CREATE INDEX IDX_6BD3AEA89AB44FE0 ON order_menu_item (menu_item_id)');
        $this->addSql('ALTER TABLE order_menu_item ADD CONSTRAINT FK_6BD3AEA88D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_menu_item ADD CONSTRAINT FK_6BD3AEA89AB44FE0 FOREIGN KEY (menu_item_id) REFERENCES menu_item (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "order_id_seq" CASCADE');
        $this->addSql('ALTER TABLE order_menu_item DROP CONSTRAINT FK_6BD3AEA88D9F6D38');
        $this->addSql('ALTER TABLE order_menu_item DROP CONSTRAINT FK_6BD3AEA89AB44FE0');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE order_menu_item');
    }
}
