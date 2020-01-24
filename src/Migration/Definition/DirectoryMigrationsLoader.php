<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration\Definition;

use BrandEmbassy\ElasticSearchMigrations\Migration\Configuration;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Nette\Utils\FileSystem;
use Nette\Utils\Finder;
use SplFileInfo;
use function sprintf;
use function uasort;

final class DirectoryMigrationsLoader implements MigrationsLoader
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var MigrationParser
     */
    private $migrationParser;

    /**
     * @var Collection<int, Migration>|Migration[]|null
     */
    private $loadedMigrations;


    public function __construct(Configuration $migrationConfig, MigrationParser $migrationParser)
    {
        $this->configuration = $migrationConfig;
        $this->migrationParser = $migrationParser;
    }


    /**
     * @return Collection<int, Migration>|Migration[]
     */
    public function loadMigrations(string $indexType): Collection
    {
        if ($this->loadedMigrations !== null) {
            return $this->loadedMigrations;
        }

        $migrations = $this->parseMigrationsFromDirectory($indexType);

        uasort(
            $migrations,
            static function (Migration $migrationA, Migration $migrationB): int {
                return $migrationA->getVersion() < $migrationB->getVersion() ? -1 : 1;
            }
        );

        $this->loadedMigrations = new ArrayCollection($migrations);

        return $this->loadedMigrations;
    }


    /**
     * @return Migration[]
     */
    private function parseMigrationsFromDirectory(string $indexType): array
    {
        $migrationsDirectory = sprintf('%s/%s', $this->configuration->getMigrationsDirectory(), $indexType);

        /** @var SplFileInfo[] $migrationFiles */
        $migrationFiles = Finder::findFiles('*.json')->in($migrationsDirectory);

        $migrations = [];

        foreach ($migrationFiles as $migrationFileInfo) {
            $migrations[] = $this->migrationParser->parse(FileSystem::read($migrationFileInfo->getPathname()));
        }

        return $migrations;
    }
}
