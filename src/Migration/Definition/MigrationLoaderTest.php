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
        $migrationsLoader->loadMigrations('default');
        $migrationsLoader->loadMigrations('default');
        $migrationsLoader->loadMigrations('default');

        $migrations = $migrationsLoader->loadMigrations('default');

        Assert::assertCount(2, $migrations);

        /** @var MigrationInterface $firstMigration */
        $firstMigration = $migrations->first();

        /** @var MigrationInterface $lastMigration */
        $lastMigration = $migrations->last();

        Assert::assertSame(1578672883, $firstMigration->getVersion());
        Assert::assertSame(1578674026, $lastMigration->getVersion());
    }
}
