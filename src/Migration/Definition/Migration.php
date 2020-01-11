<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration\Definition;

final class Migration implements MigrationInterface
{
    /**
     * @var string
     */
    private $mappingType;

    /**
     * @var array<mixed, mixed>|mixed[]
     */
    private $propertiesToUpdate;

    /**
     * @var int
     */
    private $version;


    /**
     * @param array<string, mixed>|mixed[] $propertiesToUpdate
     */
    public function __construct(string $mappingType, array $propertiesToUpdate, int $version)
    {
        $this->mappingType = $mappingType;
        $this->propertiesToUpdate = $propertiesToUpdate;
        $this->version = $version;
    }


    /**
     * @return array<string, mixed>|mixed[]
     */
    public function getPropertiesToUpdate(): array
    {
        return $this->propertiesToUpdate;
    }


    public function getVersion(): int
    {
        return $this->version;
    }


    public function getMappingType(): string
    {
        return $this->mappingType;
    }
}
