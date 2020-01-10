<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration\Definition;

interface MigrationInterface
{
    public function getIndexType(): string;


    public function getMappingType(): string;


    /**
     * @return array<mixed, mixed>|mixed[]
     */
    public function getPropertiesToUpdate(): array;


    public function getVersion(): int;
}
