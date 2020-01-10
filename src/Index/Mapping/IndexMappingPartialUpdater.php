<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Index\Mapping;

use BrandEmbassy\ElasticSearchMigrations\Client\MissingConnectionException;
use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\MigrationInterface;
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
        MigrationInterface $migrationDefinition,
        ?int $lastMigratedVersion
    ): void {
        if (!$this->elasticSearchClient->hasConnection()) {
            throw MissingConnectionException::create();
        }

        try {
            $this->updateMappingForIndex($migrationDefinition);
        } catch (ResponseException $exception) {
            throw MappingUpdateFailedException::createFromElasticSearchException(
                $exception->getElasticsearchException(),
                $migrationDefinition,
                $lastMigratedVersion
            );
        } catch (Throwable $exception) {
            throw MappingUpdateFailedException::create(
                $exception->getMessage(),
                $migrationDefinition,
                $lastMigratedVersion
            );
        }
    }


    private function updateMappingForIndex(MigrationInterface $migration): void
    {
        $elasticSearchIndex = $this->elasticSearchClient->getIndex($this->indexName);
        $elasticSearchType = $elasticSearchIndex->getType($migration->getMappingType());

        $mapping = new Mapping($elasticSearchType, $migration->getPropertiesToUpdate());

        $mapping->send();

        $this->elasticSearchClient->getIndex($this->indexName)->refresh();
    }
}
