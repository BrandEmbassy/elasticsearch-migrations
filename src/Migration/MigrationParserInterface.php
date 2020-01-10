<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration;

interface MigrationParserInterface
{
    public function objectToJson(MigrationDefinitionInterface $definition): string;


    public function jsonToObject(string $json): MigrationDefinitionInterface;
}
