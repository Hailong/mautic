<?php

namespace Mautic\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161124152153 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE page_shares (id INT AUTO_INCREMENT NOT NULL, lead_id INT DEFAULT NULL, device_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, source VARCHAR(255) NOT NULL, target VARCHAR(255) NOT NULL, shared_date DATETIME NOT NULL, status VARCHAR(255) NOT NULL, code VARCHAR(255) DEFAULT NULL, title VARCHAR(255) NOT NULL, url LONGTEXT NOT NULL, image_url LONGTEXT NOT NULL, read_count INT DEFAULT 0 NOT NULL, UNIQUE INDEX UNIQ_FF89876277153098 (code), INDEX IDX_FF89876255458D (lead_id), INDEX IDX_FF89876294A4C7D4 (device_id), INDEX IDX_FF898762727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE page_shares ADD CONSTRAINT FK_FF89876255458D FOREIGN KEY (lead_id) REFERENCES leads (id)');
        $this->addSql('ALTER TABLE page_shares ADD CONSTRAINT FK_FF89876294A4C7D4 FOREIGN KEY (device_id) REFERENCES lead_devices (id)');
        $this->addSql('ALTER TABLE page_shares ADD CONSTRAINT FK_FF898762727ACA70 FOREIGN KEY (parent_id) REFERENCES page_shares (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE page_shares DROP FOREIGN KEY FK_FF898762727ACA70');
        $this->addSql('DROP TABLE page_shares');
    }
}
