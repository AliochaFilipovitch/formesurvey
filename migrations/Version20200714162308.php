<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200714162308 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE survey ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE survey ADD CONSTRAINT FK_AD5F9BFCF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AD5F9BFCF675F31B ON survey (author_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE survey DROP FOREIGN KEY FK_AD5F9BFCF675F31B');
        $this->addSql('DROP INDEX IDX_AD5F9BFCF675F31B ON survey');
        $this->addSql('ALTER TABLE survey DROP author_id');
    }
}
