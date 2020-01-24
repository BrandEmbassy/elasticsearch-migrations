<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Index\Mapping;

use Elastica\Client;

interface IndexMappingPartialUpdaterFactory
{
    public function create(Client $elasticSearchClient, string $indexName): IndexMappingPartialUpdater;
}
