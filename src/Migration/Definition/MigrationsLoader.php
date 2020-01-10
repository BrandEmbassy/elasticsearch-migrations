<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration\Definition;

use BrandEmbassy\ElasticSearchMigrations\Migration\Configuration;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Nette\Utils\FileSystem;
use Nette\Utils\Finder;
use SplFileInfo;
use function uasort;

final class MigrationsLoader implements MigrationsLoaderInterface
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var MigrationParserInterface
     */
    private $migrationParser;

    /**
     * @var Collection<int, MigrationInterface>|MigrationInterface[]|null
     */
    private $loadedMigrations;


    public function __construct(Configuration $migrationConfig, MigrationParserInterface $migrationParser)
    {
        $this->configuration = $migrationConfig;
        $this->migrationParser = $migrationParser;
    }


    /**
     * @return Collection<int, MigrationInterface>|MigrationInterface[]
     */
    public function loadMigrations(): Collection
    {
        if ($this->loadedMigrations !== null) {
            return $this->loadedMigrations;
        }

        /** @var SplFileInfo[] $migrationsSearch */
        $migrationsSearch = Finder::findFiles('*.json')->in($this->configuration->getMigrationsDirectory());

        $migrations = [];

        foreach ($migrationsSearch as $migrationFileInfo) {
            $migrations[] = $this->migrationParser->jsonToObject(FileSystem::read($migrationFileInfo->getPathname()));
        }

        uasort(
            $migrations,
            static function (MigrationInterface $migrationA, MigrationInterface $migrationB): int {
                return $migrationA->getVersion() > $migrationB->getVersion() ? 1 : -1;
            }
        );

        $this->loadedMigrations = new ArrayCollection($migrations);

        return $this->loadedMigrations;
    }
}
