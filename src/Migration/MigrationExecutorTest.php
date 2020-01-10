<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration;

use BrandEmbassy\ElasticSearchMigrations\Client\MissingConnectionException;
use BrandEmbassy\ElasticSearchMigrations\Index\IndexNameResolver;
use BrandEmbassy\ElasticSearchMigrations\Index\Mapping\MappingUpdateFailedException;
use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\MigrationParser;
use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\MigrationsLoader;
use Elastica\Client;
use Elastica\Exception\ResponseException;
use Elastica\Index;
use Elastica\Request;
use Elastica\Response;
use Elastica\Type;
use Elasticsearch\Endpoints\Indices\Mapping\Put;
use Exception;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Throwable;

final class MigrationExecutorTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    private const TYPE_NAME = 'foo';

    /**
     * @var Client|MockInterface
     */
    private $elasticSearchClientMock;


    public function setUp(): void
    {
        parent::setUp();
        $this->elasticSearchClientMock = Mockery::mock(Client::class);
    }


    public function testMigrateFromZero(): void
    {
        $migrationExecutor = $this->createMigrationExecutor();

        $this->elasticSearchClientMock->shouldReceive('hasConnection')
            ->withNoArgs()
            ->twice()
            ->andReturnTrue();

        $elasticSearchIndexMock = $this->createElasticSearchIndexMock(2);

        $elasticSearchIndexMock->shouldReceive('requestEndpoint')
            ->with(
                Mockery::on(
                    static function (Put $put): bool {
                        return $put->getBody() === [
                                self::TYPE_NAME => [
                                    'properties' => [
                                        'id' => [
                                            'type' => 'text',
                                            'fields' => ['keyword' => ['type' => 'keyword']],
                                        ],
                                    ],
                                ],
                            ];
                    }
                )
            )
            ->once()
            ->andReturn(new Response(''));

        $elasticSearchIndexMock->shouldReceive('requestEndpoint')
            ->with(
                Mockery::on(
                    static function (Put $put): bool {
                        return $put->getBody() === [
                                self::TYPE_NAME => [
                                    'properties' => [
                                        'author' => [
                                            'type' => 'text',
                                            'fields' => ['name' => ['type' => 'keyword']],
                                        ],
                                    ],
                                ],
                            ];
                    }
                )
            )
            ->once()
            ->andReturn(new Response(''));

        $this->elasticSearchClientMock->shouldReceive('getIndex')
            ->with('testName')
            ->times(4)
            ->andReturn($elasticSearchIndexMock);

        $migrationExecutor->migrate($this->elasticSearchClientMock, null, new IndexNameResolver('testName'));
    }


    public function testMigrateOnlyNewMigrations(): void
    {
        $migrationExecutor = $this->createMigrationExecutor();

        $this->elasticSearchClientMock->shouldReceive('hasConnection')
            ->withNoArgs()
            ->once()
            ->andReturnTrue();

        $elasticSearchIndexMock = $this->createElasticSearchIndexMock(1);

        $elasticSearchIndexMock->shouldReceive('requestEndpoint')
            ->with(
                Mockery::on(
                    static function (Put $put): bool {
                        return $put->getBody() === [
                                self::TYPE_NAME => [
                                    'properties' => [
                                        'author' => [
                                            'type' => 'text',
                                            'fields' => ['name' => ['type' => 'keyword']],
                                        ],
                                    ],
                                ],
                            ];
                    }
                )
            )
            ->once()
            ->andReturn(new Response(''));

        $this->elasticSearchClientMock->shouldReceive('getIndex')
            ->with('testName')
            ->times(2)
            ->andReturn($elasticSearchIndexMock);

        $migrationExecutor->migrate($this->elasticSearchClientMock, 1578672890, new IndexNameResolver('testName'));
    }


    public function testThrowConnectionException(): void
    {
        $migrationExecutor = $this->createMigrationExecutor();

        $this->elasticSearchClientMock->shouldReceive('hasConnection')
            ->withNoArgs()
            ->once()
            ->andReturnFalse();

        $this->expectException(MissingConnectionException::class);
        $this->expectErrorMessage('Can\'t establish ElasticSearch connection');

        $migrationExecutor->migrate($this->elasticSearchClientMock, null, new IndexNameResolver('testName'));
    }


    /**
     * @dataProvider mappingUpdateFailedDataProvider
     */
    public function testThrowMappingUpdateFailedException(
        Throwable $elasticSearchException,
        string $expectedExceptionMessage
    ): void {
        $migrationExecutor = $this->createMigrationExecutor();

        $this->elasticSearchClientMock->shouldReceive('hasConnection')
            ->withNoArgs()
            ->once()
            ->andReturnTrue();

        $this->elasticSearchClientMock->shouldReceive('getIndex')
            ->with('testName')
            ->once()
            ->andThrow($elasticSearchException);

        $this->expectException(MappingUpdateFailedException::class);
        $this->expectErrorMessage($expectedExceptionMessage);

        $migrationExecutor->migrate($this->elasticSearchClientMock, null, new IndexNameResolver('testName'));
    }


    public function mappingUpdateFailedDataProvider(): array
    {
        $responseError = 'MapperParsingException[No handler for type [text] declared on field [id]]';

        $responseException = new ResponseException(
            new Request(''),
            new Response(['error' => $responseError], 500)
        );

        return [
            'response exception' => [
                'elasticSearchException' => $responseException,
                'expectedExceptionMessage' => 'MapperParsingException: ' . $responseError,
            ],
            'random exception' => [
                'elasticSearchException' => new Exception('Something went wrong'),
                'expectedExceptionMessage' => 'Something went wrong',
            ],
        ];
    }


    /**
     * @return Index|MockInterface
     */
    private function createElasticSearchIndexMock(int $migrationRuns): Index
    {
        $indexMock = Mockery::mock(Index::class);

        $indexMock->shouldReceive('getType')
            ->with('default')
            ->times($migrationRuns)
            ->andReturn(new Type($indexMock, self::TYPE_NAME));

        $indexMock->shouldReceive('refresh')
            ->withNoArgs()
            ->times($migrationRuns);

        return $indexMock;
    }


    private function createMigrationExecutor(): MigrationExecutor
    {
        $configuration = new Configuration(__DIR__ . '/Definition/__fixtures__');
        $migrationsLoader = new MigrationsLoader($configuration, new MigrationParser());

        return new MigrationExecutor($migrationsLoader);
    }
}
