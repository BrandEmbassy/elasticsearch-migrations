<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration\Definition\Json;

use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\Migration;
use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\MigrationSerializer;
use Nette\Utils\Json;

/**
 * @final
 */
class JsonMigrationSerializer implements MigrationSerializer
{
    public function serialize(Migration $migration): string
    {
        $migrationData = [
            JsonMigrationFields::VERSION => $migration->getVersion(),
            JsonMigrationFields::MAPPING_TYPE => $migration->getMappingType(),
            JsonMigrationFields::PROPERTIES_TO_UPDATE => $migration->getPropertiesToUpdate(),
        ];

        return Json::encode($migrationData, Json::PRETTY);
    }
}
