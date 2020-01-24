<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration;

use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\DirectoryMigrationsLoader;
use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\Json\JsonMigrationParser;
use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\Migration;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class MigrationFinderTest extends TestCase
{
    public function testFindLastMigration(): void
    {
        $configuration = new Configuration(__DIR__ . '/Definition/__fixtures__');
        $migrationsLoader = new DirectoryMigrationsLoader($configuration, new JsonMigrationParser());
        $migrationFinder = new MigrationFinder($migrationsLoader);

        /** @var Migration $lastMigration */
        $lastMigration = $migrationFinder->findLastMigration('default');

        Assert::assertSame(1578674026, $lastMigration->getVersion());
    }
}
