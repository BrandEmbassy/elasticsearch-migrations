<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration\Definition;

use BrandEmbassy\ElasticSearchMigrations\Migration\Configuration;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @codeCoverageIgnore
 */
final class MigrationLoaderTest extends TestCase
{
    public function testLoadMigrationsInCorrectOrder(): void
    {
        $configuration = new Configuration(__DIR__ . '/__fixtures__');
        $migrationsLoader = new MigrationsLoader($configuration, new MigrationParser());

        // test if files are loaded only once from file system
        $migrationsLoader->loadMigrations();
        $migrationsLoader->loadMigrations();
        $migrationsLoader->loadMigrations();

        $migrations = $migrationsLoader->loadMigrations();

        Assert::assertCount(2, $migrations);
        Assert::assertSame(1578672883, $migrations[0]->getVersion());
        Assert::assertSame(1578674026, $migrations[1]->getVersion());
    }
}
