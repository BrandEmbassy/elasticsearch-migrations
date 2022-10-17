<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration;

/**
 * @final
 */
class Configuration
{
    private string $migrationsDirectory;


    public function __construct(string $migrationsDirectory)
    {
        $this->migrationsDirectory = $migrationsDirectory;
    }


    public function getMigrationsDirectory(): string
    {
        return $this->migrationsDirectory;
    }
}
