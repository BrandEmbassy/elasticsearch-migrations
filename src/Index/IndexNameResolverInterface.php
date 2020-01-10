<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Index;

use BrandEmbassy\ElasticSearchMigrations\Migration\MigrationDefinitionInterface;

interface IndexNameResolverInterface
{
    public function getIndexName(MigrationDefinitionInterface $migrationDefinition): string;
}
