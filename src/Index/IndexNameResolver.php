<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Index;

use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\MigrationInterface;

final class IndexNameResolver implements IndexNameResolverInterface
{
    public function getIndexName(MigrationInterface $migrationDefinition, string $indexType): string
    {
        return $indexType;
    }
}
