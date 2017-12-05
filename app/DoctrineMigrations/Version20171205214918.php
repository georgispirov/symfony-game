<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171205214918 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product CHANGE image image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE ordered_products ADD product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ordered_products ADD CONSTRAINT FK_39EA29254584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_39EA29254584665A ON ordered_products (product_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ordered_products DROP FOREIGN KEY FK_39EA29254584665A');
        $this->addSql('DROP INDEX IDX_39EA29254584665A ON ordered_products');
        $this->addSql('ALTER TABLE ordered_products DROP product_id');
        $this->addSql('ALTER TABLE product CHANGE image image VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
