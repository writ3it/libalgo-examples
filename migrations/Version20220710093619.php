<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use DateTime;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220710093619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE task (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, scheduled_month_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, estimation INTEGER NOT NULL, salary INTEGER NOT NULL, scheduled BOOLEAN DEFAULT 0 NOT NULL)');
        $this->addSql('CREATE INDEX IDX_527EDB257BD9FF3B ON task (scheduled_month_id)');
        $sql = "INSERT INTO task (name, estimation, salary) VALUES";
        for($i = 0; $i<12; $i++){
            $sql .= '("Task #'.$i.'", '.rand(30,60).', '.rand(100,20000).'),';
        }
        $this->addSql(rtrim($sql,','));

        
        $this->addSql('CREATE TABLE working_month (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, month DATETIME NOT NULL, available_time INTEGER NOT NULL, scheduled BOOLEAN DEFAULT 0 NOT NULL)');
        $sql = "INSERT INTO working_month (month, available_time) VALUES";
        $today = new DateTime(date('Y-m-01'));
        for($i = 0; $i<3; $i++){
            $sql .= '("'.$today->format('Y-m-d').'", '.rand(100,200).'),';
            $today->modify("+1 month");
        }
        $this->addSql(rtrim($sql, ','));
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE working_month');
    }
}
