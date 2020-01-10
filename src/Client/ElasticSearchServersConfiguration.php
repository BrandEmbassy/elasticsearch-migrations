<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Client;

use function array_merge;

final class ElasticSearchServersConfiguration
{
    /**
     * @var mixed[]
     */
    private $serversConfiguration;


    public function __construct(array $serversConfiguration)
    {
        $this->serversConfiguration = $serversConfiguration;
    }


    /**
     * @return mixed[]
     */
    public function getConfiguration(string $host, int $port): array
    {
        return array_merge(
            $this->serversConfiguration,
            [
                'host' => $host,
                'port' => $port,
            ]
        );
    }
}
