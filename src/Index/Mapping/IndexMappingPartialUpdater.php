<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Index\Mapping;

use BrandEmbassy\ElasticSearchMigrations\Client\MissingConnectionException;
use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\Migration;
use Elastica\Client;
use Elastica\Exception\ResponseException;
use Elastica\Mapping;
use Throwable;

/**
 * @final
 */
class IndexMappingPartialUpdater
{
    private Client $elasticSearchClient;

    private string $indexName;


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
                $exception,
            );
        } catch (Throwable $exception) {
            throw MappingUpdateFailedException::create(
                $exception->getMessage(),
                $migration,
                $lastMigratedVersion,
                $exception,
            );
        }
    }


    private function updateMappingForIndex(Migration $migration): void
    {
        $elasticSearchIndex = $this->elasticSearchClient->getIndex($this->indexName);

        $mapping = new Mapping($migration->getPropertiesToUpdate());
        $mapping->send($elasticSearchIndex);

        $this->elasticSearchClient->getIndex($this->indexName)->refresh();
    }
}
