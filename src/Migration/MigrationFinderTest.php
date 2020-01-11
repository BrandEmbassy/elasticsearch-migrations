<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration;

use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\MigrationInterface;
use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\MigrationParser;
use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\MigrationsLoader;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class MigrationFinderTest extends TestCase
{
    public function testFindLastMigration(): void
    {
        $configuration = new Configuration(__DIR__ . '/Definition/__fixtures__');
        $migrationsLoader = new MigrationsLoader($configuration, new MigrationParser());
        $migrationFinder = new MigrationFinder($migrationsLoader);

        /** @var MigrationInterface $lastMigration */
        $lastMigration = $migrationFinder->findLastMigration('default');

        Assert::assertSame(1578674026, $lastMigration->getVersion());
    }
}
