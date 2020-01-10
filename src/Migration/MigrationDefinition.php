<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration;

final class MigrationDefinition implements MigrationDefinitionInterface
{
    /**
     * @var string
     */
    private $mappingType;

    /**
     * @var mixed[]
     */
    private $propertiesToUpdate;

    /**
     * @var int
     */
    private $version;

    /**
     * @var string
     */
    private $indexType;


    public function __construct(string $indexType, string $mappingType, array $propertiesToUpdate, int $version)
    {
        $this->indexType = $indexType;
        $this->mappingType = $mappingType;
        $this->propertiesToUpdate = $propertiesToUpdate;
        $this->version = $version;
    }


    public function getIndexType(): string
    {
        return $this->indexType;
    }


    /**
     * @return mixed[]
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
