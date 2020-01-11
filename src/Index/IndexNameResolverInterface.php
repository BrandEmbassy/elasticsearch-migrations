<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Index;

use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\MigrationInterface;

interface IndexNameResolverInterface
{
    public function getIndexName(MigrationInterface $migrationDefinition, string $indexType): string;
}
