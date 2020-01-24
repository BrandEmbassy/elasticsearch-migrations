<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration\Definition\Json;

use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\Migration;
use PHPStan\Testing\TestCase;
use PHPUnit\Framework\Assert;

/**
 * @codeCoverageIgnore
 */
final class JsonMigrationSerializerTest extends TestCase
{
    public function testParseToJson(): void
    {
        $migration = new Migration('default', ['foo' => 'bar'], 12345);

        $jsonMigrationSerializer = new JsonMigrationSerializer();

        Assert::assertSame(
            '{
    "version": 12345,
    "mappingType": "default",
    "propertiesToUpdate": {
        "foo": "bar"
    }
}',
            $jsonMigrationSerializer->serialize($migration)
        );
    }
}
