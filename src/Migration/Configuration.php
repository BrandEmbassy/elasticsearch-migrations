<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration;

final class Configuration
{
    /**
     * @var string
     */
    private $migrationsDirectory;


    public function __construct(string $migrationsDirectory)
    {
        $this->migrationsDirectory = $migrationsDirectory;
    }


    public function getMigrationsDirectory(): string
    {
        return $this->migrationsDirectory;
    }
}
