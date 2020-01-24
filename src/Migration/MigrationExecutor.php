<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration;

use BrandEmbassy\ElasticSearchMigrations\Index\IndexNameResolverInterface;
use BrandEmbassy\ElasticSearchMigrations\Index\Mapping\IndexMappingPartialUpdater;
use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\Migration;
use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\MigrationsLoader;
use Doctrine\Common\Collections\Collection;
use Elastica\Client;

final class MigrationExecutor
{
    /**
     * @var MigrationsLoader
     */
    private $migrationsLoader;


    public function __construct(MigrationsLoader $migrationsLoader)
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
     * @return Collection<int, Migration>|Migration[]
     */
    private function getMigrations(?int $lastVersion, string $indexType): Collection
    {
        $allMigrations = $this->migrationsLoader->loadMigrations($indexType);

        if ($lastVersion === null) {
            return $allMigrations;
        }

        return $allMigrations->filter(
            static function (Migration $migrationDefinition) use ($lastVersion): bool {
                return $migrationDefinition->getVersion() > $lastVersion;
            }
        );
    }
}
