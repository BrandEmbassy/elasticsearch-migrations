<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration;

use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\MigrationInterface;
use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\MigrationsLoaderInterface;

final class MigrationFinder
{
    /**
     * @var MigrationsLoaderInterface
     */
    private $migrationsLoader;


    public function __construct(MigrationsLoaderInterface $migrationsLoader)
    {
        $this->migrationsLoader = $migrationsLoader;
    }


    public function findLastMigration(string $indexType): ?MigrationInterface
    {
        $lastMigration = $this->migrationsLoader->loadMigrations($indexType)->last();

        return $lastMigration !== false ? $lastMigration : null;
    }
}
