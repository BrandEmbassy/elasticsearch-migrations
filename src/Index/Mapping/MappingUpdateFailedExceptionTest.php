<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Index\Mapping;

use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\Migration;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @final
 */
class MappingUpdateFailedExceptionTest extends TestCase
{
    public function testGetValidObjects(): void
    {
        $migration = new Migration('default', ['foo' => 'bar'], 2);
        $exception = MappingUpdateFailedException::create('Foo', $migration, 1);

        Assert::assertSame(1, $exception->getLastVersion());
        Assert::assertSame($migration, $exception->getMigration());
    }
}
