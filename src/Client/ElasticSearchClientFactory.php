<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Client;

use Elastica\Client;

final class ElasticSearchClientFactory implements ElasticSearchClientFactoryInterface
{
    /**
     * @var ElasticSearchServersConfiguration
     */
    private $serversConfiguration;


    public function __construct(ElasticSearchServersConfiguration $serversConfiguration)
    {
        $this->serversConfiguration = $serversConfiguration;
    }


    public function create(string $host, int $port): Client
    {
        $configuration = $this->serversConfiguration->getConfiguration($host, $port);

        return new Client(['servers' => $configuration], null);
    }
}
