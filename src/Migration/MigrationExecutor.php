<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration;

use BrandEmbassy\ElasticSearchMigrations\Index\IndexNameResolverInterface;
use BrandEmbassy\ElasticSearchMigrations\Index\Mapping\IndexMappingPartialUpdaterFactory;
use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\Migration;
use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\MigrationsLoader;
use Doctrine\Common\Collections\Collection;
use Elastica\Client;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use function sprintf;

/**
 * @final
 */
class MigrationExecutor
{
    private MigrationsLoader $migrationsLoader;

    private IndexMappingPartialUpdaterFactory $indexMappingPartialUpdaterFactory;

    private LoggerInterface $logger;


    public function __construct(
        MigrationsLoader $migrationsLoader,
        IndexMappingPartialUpdaterFactory $indexMappingPartialUpdaterFactory,
        ?LoggerInterface $logger = null
    ) {
        $this->migrationsLoader = $migrationsLoader;
        $this->indexMappingPartialUpdaterFactory = $indexMappingPartialUpdaterFactory;
        $this->logger = $logger ?? new NullLogger();
    }


    public function migrate(
        Client $elasticSearchClient,
        ?int $lastVersion,
        IndexNameResolverInterface $indexNameResolver,
        string $indexType
    ): ?int {
        $migrations = $this->getMigrations($lastVersion, $indexType);

        $lastMigratedVersion = $lastVersion;

        foreach ($migrations as $migration) {
            $indexName = $indexNameResolver->getIndexName($migration, $indexType);

            $this->executeMigration($elasticSearchClient, $migration, $indexName, $lastMigratedVersion);

            $lastMigratedVersion = $migration->getVersion();
        }

        return $lastMigratedVersion;
    }


    private function executeMigration(
        Client $elasticSearchClient,
        Migration $migration,
        string $indexName,
        ?int $lastMigratedVersion
    ): void {
        $this->logger->info(
            sprintf('%s index mapping migration started, current version %d', $indexName, $lastMigratedVersion ?? 0),
        );

        $indexMappingPartialUpdater = $this->indexMappingPartialUpdaterFactory->create(
            $elasticSearchClient,
            $indexName,
        );

        $indexMappingPartialUpdater->update($migration, $lastMigratedVersion);

        $this->logger->info(
            sprintf('%s index mapping migration done, current version %d', $indexName, $migration->getVersion()),
        );
    }


    /**
     * @return Collection<int, Migration>|Migration[]
     */
    private function getMigrations(?int $lastVersion, string $indexType): Collection
    {
        $allMigrations = $this->migrationsLoader->loadMigrations($indexType);

        if ($lastVersion === null) {
            return $allMigrations;
        }

        return $allMigrations->filter(
            static fn(Migration $migrationDefinition): bool => $migrationDefinition->getVersion() > $lastVersion,
        );
    }
}
