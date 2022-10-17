<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Index\Mapping;

use Elastica\Client;

/**
 * @final
 */
class BasicIndexMappingPartialUpdaterFactory implements IndexMappingPartialUpdaterFactory
{
    public function create(Client $elasticSearchClient, string $indexName): IndexMappingPartialUpdater
    {
        return new IndexMappingPartialUpdater($elasticSearchClient, $indexName);
    }
}
