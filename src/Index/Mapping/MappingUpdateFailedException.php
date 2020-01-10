<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Index\Mapping;

use BrandEmbassy\ElasticSearchMigrations\Migration\MigrationDefinitionInterface;
use Elastica\Exception\ElasticsearchException;
use Exception;
use Throwable;
use function sprintf;

final class MappingUpdateFailedException extends Exception
{
    /**
     * @var int
     */
    private $lastVersion;

    /**
     * @var MigrationDefinitionInterface
     */
    private $migrationDefinition;


    public function __construct(
        string $message,
        MigrationDefinitionInterface $migrationDefinition,
        ?int $lastVersion,
        Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
        $this->migrationDefinition = $migrationDefinition;
        $this->lastVersion = $lastVersion;
    }


    /**
     * @return int|null
     */
    public function getLastVersion(): ?int
    {
        return $this->lastVersion;
    }


    /**
     * @return MigrationDefinitionInterface
     */
    public function getMigrationDefinition(): MigrationDefinitionInterface
    {
        return $this->migrationDefinition;
    }


    public static function createFromElasticSearchException(
        ElasticsearchException $exception,
        MigrationDefinitionInterface $migrationDefinition,
        ?int $lastVersion
    ): self {
        return self::create(
            sprintf('%s: %s', $exception->getExceptionName(), $exception->getMessage()),
            $migrationDefinition,
            $lastVersion
        );
    }


    public static function create(
        string $message,
        MigrationDefinitionInterface $migrationDefinition,
        ?int $lastVersion
    ): self {
        return new self($message, $migrationDefinition, $lastVersion);
    }
}
