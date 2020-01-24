<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Index\Mapping;

use BrandEmbassy\ElasticSearchMigrations\Client\MissingConnectionException;
use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\Migration;
use Elastica\Client;
use Elastica\Exception\ResponseException;
use Elastica\Type\Mapping;
use Throwable;

final class IndexMappingPartialUpdater
{
    /**
     * @var Client
     */
    private $elasticSearchClient;

    /**
     * @var string
     */
    private $indexName;


    public function __construct(Client $elasticSearchClient, string $indexName)
    {
        $this->elasticSearchClient = $elasticSearchClient;
        $this->indexName = $indexName;
    }


    /**
     * @throws MappingUpdateFailedException
     * @throws MissingConnectionException
     */
    public function update(
        Migration $migration,
        ?int $lastMigratedVersion
    ): void {
        if (!$this->elasticSearchClient->hasConnection()) {
            throw MissingConnectionException::create();
        }

        try {
            $this->updateMappingForIndex($migration);
        } catch (ResponseException $exception) {
            throw MappingUpdateFailedException::createFromElasticSearchException(
                $exception->getElasticsearchException(),
                $migration,
                $lastMigratedVersion,
                $exception
            );
        } catch (Throwable $exception) {
            throw MappingUpdateFailedException::create(
                $exception->getMessage(),
                $migration,
                $lastMigratedVersion,
                $exception
            );
        }
    }


    private function updateMappingForIndex(Migration $migration): void
    {
        $elasticSearchIndex = $this->elasticSearchClient->getIndex($this->indexName);
        $elasticSearchType = $elasticSearchIndex->getType($migration->getMappingType());

        $mapping = new Mapping($elasticSearchType, $migration->getPropertiesToUpdate());

        $mapping->send();

        $this->elasticSearchClient->getIndex($this->indexName)->refresh();
    }
}
