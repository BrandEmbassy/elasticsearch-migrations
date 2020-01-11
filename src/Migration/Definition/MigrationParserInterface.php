<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration\Definition;

interface MigrationParserInterface
{
    public function objectToJson(MigrationInterface $definition): string;


    public function jsonToObject(string $json): MigrationInterface;
}
