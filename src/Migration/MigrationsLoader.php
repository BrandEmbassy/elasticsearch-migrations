<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Nette\Utils\FileSystem;
use Nette\Utils\Finder;
use SplFileInfo;
use function uasort;

final class MigrationsLoader implements MigrationsLoaderInterface
{
    /**
     * @var MigrationConfig
     */
    private $migrationConfig;

    /**
     * @var MigrationParserInterface
     */
    private $migrationParser;

    /**
     * @var Collection|MigrationDefinitionInterface[]|null
     */
    private $loadedMigrations;


    public function __construct(MigrationConfig $migrationConfig, MigrationParserInterface $migrationParser)
    {
        $this->migrationConfig = $migrationConfig;
        $this->migrationParser = $migrationParser;
    }


    public function loadMigrations(): Collection
    {
        if ($this->loadedMigrations !== null) {
            return $this->loadedMigrations;
        }

        /** @var SplFileInfo[] $migrationsSearch */
        $migrationsSearch = Finder::findFiles('*.json')->in($this->migrationConfig->getMigrationsDirectory());

        $migrations = [];

        foreach ($migrationsSearch as $migrationFileInfo) {
            $migrations[] = $this->migrationParser->jsonToObject(FileSystem::read($migrationFileInfo->getPathname()));
        }

        uasort(
            $migrations,
            static function (MigrationDefinitionInterface $migrationA, MigrationDefinitionInterface $migrationB): bool {
                return $migrationA->getVersion() > $migrationB->getVersion();
            }
        );

        $this->loadedMigrations = new ArrayCollection($migrations);

        return $this->loadedMigrations;
    }
}
