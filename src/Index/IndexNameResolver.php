<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Index;

use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\MigrationInterface;

final class IndexNameResolver implements IndexNameResolverInterface
{
    /**
     * @var string
     */
    private $indexName;


    public function __construct(string $indexName)
    {
        $this->indexName = $indexName;
    }


    public function getIndexName(MigrationInterface $migrationDefinition): string
    {
        return $this->indexName;
    }
}
