<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration;

use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\Migration;
use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\MigrationsLoader;

/**
 * @final
 */
class MigrationFinder
{
    private MigrationsLoader $migrationsLoader;


    public function __construct(MigrationsLoader $migrationsLoader)
    {
        $this->migrationsLoader = $migrationsLoader;
    }


    public function findLastMigration(string $indexType): ?Migration
    {
        $lastMigration = $this->migrationsLoader->loadMigrations($indexType)->last();

        return $lastMigration !== false ? $lastMigration : null;
    }
}
