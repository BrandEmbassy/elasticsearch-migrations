<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration\Definition;

interface MigrationSerializer
{
    public function serialize(Migration $migration): string;
}
