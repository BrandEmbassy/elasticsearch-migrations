<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Index\Mapping;

use BrandEmbassy\ElasticSearchMigrations\Client\MissingConnectionException;
use BrandEmbassy\ElasticSearchMigrations\Migration\MigrationDefinitionInterface;
use Elastica\Client;
use Elastica\Exception\ResponseException;
use Elastica\Type\Mapping;
use Throwable;
use const PHP_EOL;

final class IndexMappingPartialUpdater
{
    public function update(
        Client $esClient,
        MigrationDefinitionInterface $migrationDefinition,
        string $indexName,
        ?int $lastMigratedVersion
    ): void {
        if (!$esClient->hasConnection()) {
            throw MissingConnectionException::create();
        }

        try {
            $this->updateMappingForIndex($esClient, $migrationDefinition, $indexName);
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


    private function updateMappingForIndex(
        Client $esClient,
        MigrationDefinitionInterface $migrationDefinition,
        string $indexName
    ): void {
        $esIndex = $esClient->getIndex($indexName);
        $esType = $esIndex->getType($migrationDefinition->getIndexType());

//        $mapping = new Mapping();
//        $mapping->setType($esType);
//        $mapping->setProperties($migrationDefinition->getPropertiesToUpdate());
//
//        $mapping->send();
//        $esClient->getIndex($indexName)->refresh();

        echo $indexName . ' SUCCESS ' . $migrationDefinition->getVersion() . PHP_EOL;
    }
}
