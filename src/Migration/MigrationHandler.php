<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration;

use BrandEmbassy\ElasticSearchMigrations\Index\IndexNameResolverInterface;
use BrandEmbassy\ElasticSearchMigrations\Index\Mapping\IndexMappingPartialUpdater;
use Doctrine\Common\Collections\Collection;
use Elastica\Client;

final class MigrationHandler
{
    /**
     * @var MigrationConfig
     */
    private $migrationConfig;

    /**
     * @var IndexMappingPartialUpdater
     */
    private $indexMappingPartialUpdater;

    /**
     * @var MigrationsLoaderInterface
     */
    private $migrationsLoader;


    public function __construct(
        MigrationsLoaderInterface $migrationsLoader,
        IndexMappingPartialUpdater $indexMappingPartialUpdater
    ) {
        $this->indexMappingPartialUpdater = $indexMappingPartialUpdater;
        $this->migrationsLoader = $migrationsLoader;
    }


    public function migrate(Client $esClient, ?int $lastVersion, IndexNameResolverInterface $indexNameResolver): int
    {
        $migrations = $this->getMigrations($lastVersion);

        $lastMigratedVersion = $lastVersion;

        foreach ($migrations as $migration) {
            $indexName = $indexNameResolver->getIndexName($migration);

            $this->indexMappingPartialUpdater->update($esClient, $migration, $indexName, $lastMigratedVersion);

            $lastMigratedVersion = $migration->getVersion();
        }

        return $lastMigratedVersion;
    }


    /**
     * @return Collection|MigrationDefinitionInterface[]
     */
    private function getMigrations(?int $lastVersion): Collection
    {
        $allMigrations = $this->migrationsLoader->loadMigrations();

        if ($lastVersion === null) {
            return $allMigrations;
        }

        return $allMigrations->filter(
            static function (MigrationDefinitionInterface $migrationDefinition) use ($lastVersion): bool {
                return $migrationDefinition->getVersion() > $lastVersion;
            }
        );
    }
}
