<?php

declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20230213211534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create user table';
    }

    /**
     * @param Schema $schema
     * @throws SchemaException
     */
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('user');
        $table->addColumn('id', Types::INTEGER, [
            'autoincrement' => true,
        ]);
        $table->addColumn('email', Types::STRING, [
            'length' => 180,
        ]);
        $table->addColumn('name', Types::STRING);
        $table->addColumn('password', Types::STRING);
        $table->addColumn('created_at', Types::DATETIMETZ_MUTABLE);
        $table->addColumn('updated_at', Types::DATETIMETZ_MUTABLE);
        $table->addColumn('deleted_at', Types::DATETIMETZ_MUTABLE, [
            'notnull' => false,
        ]);

        $table->setPrimaryKey(['id']);
    }

    /**
     * @param Schema $schema
     * @throws SchemaException
     */
    public function down(Schema $schema): void
    {
        $schema->dropTable('user');
    }
}
