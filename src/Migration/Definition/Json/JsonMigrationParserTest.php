<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration\Definition\Json;

use Nette\Utils\FileSystem;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @codeCoverageIgnore
 */
final class JsonMigrationParserTest extends TestCase
{
    public function testParseToObject(): void
    {
        $migrationParser = new JsonMigrationParser();

        $migration = $migrationParser->parse(
            FileSystem::read(__DIR__ . '/../__fixtures__/default/migration_1578672883.json')
        );

        $expectedMigrationPropertiesToUpdate = [
            'id' => [
                'type' => 'text',
                'fields' => [
                    'keyword' => ['type' => 'keyword'],
                ],
            ],
        ];

        Assert::assertSame('default', $migration->getMappingType());
        Assert::assertSame(1578672883, $migration->getVersion());
        Assert::assertSame($expectedMigrationPropertiesToUpdate, $migration->getPropertiesToUpdate());
    }
}
