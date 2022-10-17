<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration\Definition;

use BrandEmbassy\ElasticSearchMigrations\Migration\Configuration;
use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\Json\JsonMigrationParser;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @codeCoverageIgnore
 * @final
 */
class DirectoryMigrationLoaderTest extends TestCase
{
    public function testLoadMigrationsInCorrectOrder(): void
    {
        $configuration = new Configuration(__DIR__ . '/__fixtures__');
        $directoryMigrationsLoader = new DirectoryMigrationsLoader($configuration, new JsonMigrationParser());

        $migrations = $directoryMigrationsLoader->loadMigrations('default');

        Assert::assertCount(2, $migrations);

        /** @var Migration $firstMigration */
        $firstMigration = $migrations->first();

        /** @var Migration $lastMigration */
        $lastMigration = $migrations->last();

        Assert::assertSame(1578672883, $firstMigration->getVersion());
        Assert::assertSame(1578674026, $lastMigration->getVersion());
    }


    public function testLoadMigrationsFromDirectoryOnlyOnce(): void
    {
        $configuration = new Configuration(__DIR__ . '/__fixtures__');
        $migrationsLoader = new DirectoryMigrationsLoader($configuration, new JsonMigrationParser());

        $firstLoad = $migrationsLoader->loadMigrations('default');
        $secondLoad = $migrationsLoader->loadMigrations('default');

        Assert::assertSame($firstLoad, $secondLoad);
    }
}
