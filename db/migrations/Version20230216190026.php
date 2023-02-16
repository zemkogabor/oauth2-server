<?php

declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20230216190026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create refresh_token table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('refresh_token');
        $table->addColumn('id', Types::INTEGER, [
            'autoincrement' => true,
        ]);
        $table->addColumn('access_token_id', Types::INTEGER);
        $table->addColumn('token', Types::STRING);
        $table->addColumn('is_revoke', Types::BOOLEAN);
        $table->addColumn('expiry_at', Types::DATETIMETZ_MUTABLE);
        $table->addColumn('created_at', Types::DATETIMETZ_MUTABLE);
        $table->addColumn('updated_at', Types::DATETIMETZ_MUTABLE);
        $table->addForeignKeyConstraint('access_token', ['access_token_id'], ['id']);
        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('refresh_token');
    }
}
