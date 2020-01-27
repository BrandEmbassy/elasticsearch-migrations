<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration\Definition\Json;

use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\Migration;
use Nette\Utils\FileSystem;
use PHPStan\Testing\TestCase;
use PHPUnit\Framework\Assert;
use function preg_replace;

/**
 * @codeCoverageIgnore
 */
final class JsonMigrationSerializerTest extends TestCase
{
    public function testParseToJson(): void
    {
        $migration = new Migration(
            'default',
            [
                'author' => [
                    'type' => 'text',
                    'fields' => ['name' => ['type' => 'keyword']],
                ],
            ],
            1578674026
        );

        $jsonMigrationSerializer = new JsonMigrationSerializer();

        $this->assertEscapedJson(
            FileSystem::read(__DIR__ . '/../__fixtures__/default/migration_1578674026.json'),
            $jsonMigrationSerializer->serialize($migration)
        );
    }


    private function assertEscapedJson(string $expectedJson, string $actualJson): void
    {
        $clearExpectedJson = preg_replace('/\s+/', '', $expectedJson);
        $clearActualJson = preg_replace('/\s+/', '', $actualJson);

        Assert::assertSame($clearExpectedJson, $clearActualJson);
    }
}
