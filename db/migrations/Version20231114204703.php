<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231114204703 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $table = $schema->getTable('client');
        $table->modifyColumn('secret', ['notnull' => false, 'default' => null]);
    }

    public function down(Schema $schema): void
    {
        $table = $schema->getTable('client');
        $table->modifyColumn('secret', ['notnull' => true]);
    }
}
