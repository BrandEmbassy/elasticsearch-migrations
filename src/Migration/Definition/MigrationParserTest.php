<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration\Definition;

use Nette\Utils\FileSystem;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @codeCoverageIgnore
 */
final class MigrationParserTest extends TestCase
{
    public function testParseToObject(): void
    {
        $migrationParser = new MigrationParser();

        $migration = $migrationParser->jsonToObject(
            FileSystem::read(__DIR__ . '/__fixtures__/default/migration_1578672883.json')
        );

        Assert::assertSame('default', $migration->getMappingType());
        Assert::assertSame(1578672883, $migration->getVersion());
        Assert::assertSame(
            [
                'id' => [
                    'type' => 'text',
                    'fields' => [
                        'keyword' => ['type' => 'keyword'],
                    ],
                ],
            ],
            $migration->getPropertiesToUpdate()
        );
    }


    public function testParseToJson(): void
    {
        $migration = new Migration('default', ['foo' => 'bar'], 12345);

        $migrationParser = new MigrationParser();

        Assert::assertSame(
            '{
    "version": 12345,
    "mappingType": "default",
    "propertiesToUpdate": {
        "foo": "bar"
    }
}',
            $migrationParser->objectToJson($migration)
        );
    }
}
