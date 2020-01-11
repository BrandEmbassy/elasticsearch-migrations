<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration\Definition;

use Nette\Utils\Json;

final class MigrationParser implements MigrationParserInterface
{
    private const MAPPING_TYPE = 'mappingType';
    private const PROPERTIES_TO_UPDATE = 'propertiesToUpdate';
    private const VERSION = 'version';


    public function objectToJson(MigrationInterface $definition): string
    {
        $migrationData = [
            self::VERSION => $definition->getVersion(),
            self::MAPPING_TYPE => $definition->getMappingType(),
            self::PROPERTIES_TO_UPDATE => $definition->getPropertiesToUpdate(),
        ];

        return Json::encode($migrationData, Json::PRETTY);
    }


    public function jsonToObject(string $json): MigrationInterface
    {
        $fileData = Json::decode($json, Json::FORCE_ARRAY);

        return new Migration(
            $fileData[self::MAPPING_TYPE],
            $fileData[self::PROPERTIES_TO_UPDATE],
            $fileData[self::VERSION]
        );
    }
}
