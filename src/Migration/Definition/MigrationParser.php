<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration\Definition;

interface MigrationParser
{
    public function parse(string $rawMigration): Migration;
}
