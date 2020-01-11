<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration;

use BrandEmbassy\ElasticSearchMigrations\Index\IndexNameResolverInterface;
use BrandEmbassy\ElasticSearchMigrations\Index\Mapping\IndexMappingPartialUpdater;
use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\MigrationInterface;
use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\MigrationsLoaderInterface;
use Doctrine\Common\Collections\Collection;
use Elastica\Client;

final class MigrationExecutor
{
    /**
     * @var MigrationsLoaderInterface
     */
    private $migrationsLoader;


    public function __construct(MigrationsLoaderInterface $migrationsLoader)
    {
        $this->migrationsLoader = $migrationsLoader;
    }


    public function migrate(Client $esClient, ?int $lastVersion, IndexNameResolverInterface $indexNameResolver, string $indexType): ?int
    {
        $migrations = $this->getMigrations($lastVersion, $indexType);

        $lastMigratedVersion = $lastVersion;

        foreach ($migrations as $migration) {
            $indexName = $indexNameResolver->getIndexName($migration, $indexType);

            $indexMappingPartialUpdater = new IndexMappingPartialUpdater($esClient, $indexName);

            $indexMappingPartialUpdater->update($migration, $lastMigratedVersion);

            $lastMigratedVersion = $migration->getVersion();
        }

        return $lastMigratedVersion;
    }


    /**
     * @return Collection<int, MigrationInterface>|MigrationInterface[]
     */
    private function getMigrations(?int $lastVersion, string $indexType): Collection
    {
        $allMigrations = $this->migrationsLoader->loadMigrations($indexType);

        if ($lastVersion === null) {
            return $allMigrations;
        }

        return $allMigrations->filter(
            static function (MigrationInterface $migrationDefinition) use ($lastVersion): bool {
                return $migrationDefinition->getVersion() > $lastVersion;
            }
        );
    }
}
