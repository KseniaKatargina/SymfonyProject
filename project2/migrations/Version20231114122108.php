<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231114122108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_f52993989395c3f3');
        $this->addSql('CREATE INDEX IDX_F52993989395C3F3 ON "order" (customer_id)');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT fk_8d93d6496d128938');
        $this->addSql('DROP INDEX uniq_8d93d6496d128938');
        $this->addSql('ALTER TABLE "user" DROP user_order_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" ADD user_order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT fk_8d93d6496d128938 FOREIGN KEY (user_order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d6496d128938 ON "user" (user_order_id)');
        $this->addSql('DROP INDEX IDX_F52993989395C3F3');
        $this->addSql('CREATE UNIQUE INDEX uniq_f52993989395c3f3 ON "order" (customer_id)');
    }
}
