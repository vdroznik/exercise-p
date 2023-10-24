<?php

declare(strict_types=1);

namespace ExercisePromo\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231024111011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Users Promocodes';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('users_promos');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->setPrimaryKey(["id"]);
        $table->addColumn('user_id', 'integer', ['notnull' => true]);
        $table->addColumn('code', 'string', ['notnull' => true, 'length' => 10]);
        $table->addColumn('ip_packed', 'integer', ['notnull' => true]);

        $table->addColumn('created_at', 'datetime', ['notnull' => true])->setDefault('CURRENT_TIMESTAMP');
        $table->addColumn('updated_at', 'datetime', ['columnDefinition' => 'DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP']);
    }

    public function down(Schema $schema): void
    {

    }
}
