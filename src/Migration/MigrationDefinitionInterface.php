<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration;

interface MigrationDefinitionInterface
{
    public function getIndexType(): string;


    public function getMappingType(): string;


    public function getPropertiesToUpdate(): array;


    public function getVersion(): int;
}
