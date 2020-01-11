<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Index\Mapping;

use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\MigrationInterface;
use Elastica\Exception\ElasticsearchException;
use Exception;
use Throwable;
use function sprintf;

final class MappingUpdateFailedException extends Exception
{
    /**
     * @var int|null
     */
    private $lastVersion;

    /**
     * @var MigrationInterface
     */
    private $migration;


    public function __construct(
        string $message,
        MigrationInterface $migration,
        ?int $lastVersion,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
        $this->migration = $migration;
        $this->lastVersion = $lastVersion;
    }


    public function getLastVersion(): ?int
    {
        return $this->lastVersion;
    }


    public function getMigration(): MigrationInterface
    {
        return $this->migration;
    }


    public static function createFromElasticSearchException(
        ElasticsearchException $exception,
        MigrationInterface $migration,
        ?int $lastVersion,
        Throwable $previous
    ): self {
        return self::create(
            sprintf('%s: %s', $exception->getExceptionName(), $exception->getMessage()),
            $migration,
            $lastVersion,
            $previous
        );
    }


    public static function create(
        string $message,
        MigrationInterface $migration,
        ?int $lastVersion,
        ?Throwable $previous = null
    ): self {
        return new self($message, $migration, $lastVersion, $previous);
    }
}
