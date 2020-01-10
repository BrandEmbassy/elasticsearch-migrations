<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Client;

use Elastica\Client;

interface ElasticSearchClientFactoryInterface
{
    public function create(string $host, int $port): Client;
}
