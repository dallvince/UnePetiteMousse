<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220506141307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brewries (id INT AUTO_INCREMENT NOT NULL, countries_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_C8663C47AEBAE514 (countries_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, users_id INT DEFAULT NULL, products_id INT DEFAULT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5F9E962A67B3B43D (users_id), INDEX IDX_5F9E962A6C8A81A9 (products_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE countries (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, flag VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE styles (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE brewries ADD CONSTRAINT FK_C8663C47AEBAE514 FOREIGN KEY (countries_id) REFERENCES countries (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A67B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A6C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE products ADD styles_id INT DEFAULT NULL, ADD brewries_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5AB0A3C560 FOREIGN KEY (styles_id) REFERENCES styles (id)');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5AC4B6E32F FOREIGN KEY (brewries_id) REFERENCES brewries (id)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5AB0A3C560 ON products (styles_id)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5AC4B6E32F ON products (brewries_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5AC4B6E32F');
        $this->addSql('ALTER TABLE brewries DROP FOREIGN KEY FK_C8663C47AEBAE514');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5AB0A3C560');
        $this->addSql('DROP TABLE brewries');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE countries');
        $this->addSql('DROP TABLE styles');
        $this->addSql('DROP INDEX IDX_B3BA5A5AB0A3C560 ON products');
        $this->addSql('DROP INDEX IDX_B3BA5A5AC4B6E32F ON products');
        $this->addSql('ALTER TABLE products DROP styles_id, DROP brewries_id');
    }
}
