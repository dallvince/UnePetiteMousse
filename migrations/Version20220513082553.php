<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220513082553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products ADD stocks_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5AFACB6020 FOREIGN KEY (stocks_id) REFERENCES stocks (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B3BA5A5AFACB6020 ON products (stocks_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5AFACB6020');
        $this->addSql('DROP INDEX UNIQ_B3BA5A5AFACB6020 ON products');
        $this->addSql('ALTER TABLE products DROP stocks_id');
    }
}
