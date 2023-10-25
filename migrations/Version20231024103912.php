<?php

declare(strict_types=1);

namespace ExercisePromo\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231024103912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Users';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('users');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->setPrimaryKey(["id"]);
        $table->addColumn('username', 'string', ['notnull' => true]);
        $table->addIndex(['username'], 'username');

        $table->addColumn('created_at', 'datetime', ['notnull' => true])->setDefault('CURRENT_TIMESTAMP');
        $table->addColumn('updated_at', 'datetime', ['columnDefinition' => 'DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('users');
    }
}
