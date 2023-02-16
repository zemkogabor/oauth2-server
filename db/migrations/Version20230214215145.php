<?php

declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;
use Ramsey\Uuid\Doctrine\UuidType;

final class Version20230214215145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create client table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('client');
        $table->addColumn('id', Types::INTEGER, [
            'autoincrement' => true,
        ]);
        $table->addColumn('name', Types::STRING);
        $table->addColumn('uuid', UuidType::NAME);
        $table->addColumn('secret', Types::STRING);
        $table->addColumn('redirect_uri', Types::STRING);
        $table->addColumn('is_confidential', Types::BOOLEAN);
        $table->addColumn('created_at', Types::DATETIMETZ_MUTABLE);
        $table->addColumn('updated_at', Types::DATETIMETZ_MUTABLE);
        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('client');
    }
}
