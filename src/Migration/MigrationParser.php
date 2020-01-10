<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration;

use Nette\Utils\Json;

final class MigrationParser implements MigrationParserInterface
{
    private const INDEX_TYPE = 'indexType';
    private const MAPPING_TYPE = 'mappingType';
    private const PROPERTIES_TO_UPDATE = 'propertiesToUpdate';
    private const VERSION = 'version';


    public function objectToJson(MigrationDefinitionInterface $definition): string
    {
        $migrationData = [
            self::VERSION => $definition->getVersion(),
            self::INDEX_TYPE => $definition->getIndexType(),
            self::MAPPING_TYPE => $definition->getMappingType(),
            self::PROPERTIES_TO_UPDATE => $definition->getPropertiesToUpdate(),
        ];

        return Json::encode($migrationData, Json::PRETTY);
    }


    public function jsonToObject(string $json): MigrationDefinitionInterface
    {
        $fileData = Json::decode($json, Json::FORCE_ARRAY);

        return new MigrationDefinition(
            $fileData[self::INDEX_TYPE],
            $fileData[self::MAPPING_TYPE],
            $fileData[self::PROPERTIES_TO_UPDATE],
            $fileData[self::VERSION]
        );
    }
}
