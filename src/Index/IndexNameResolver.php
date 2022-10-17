<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Index;

use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\Migration;

/**
 * @final
 */
class IndexNameResolver implements IndexNameResolverInterface
{
    public function getIndexName(Migration $migration, string $indexType): string
    {
        return $indexType;
    }
}
