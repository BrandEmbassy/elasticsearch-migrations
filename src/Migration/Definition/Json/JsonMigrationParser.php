<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration\Definition\Json;

use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\Migration;
use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\MigrationParser;
use Nette\Utils\Json;

final class JsonMigrationParser implements MigrationParser
{
    public function parse(string $rawMigration): Migration
    {
        $fileData = Json::decode($rawMigration, Json::FORCE_ARRAY);

        return new Migration(
            $fileData[JsonMigrationFields::MAPPING_TYPE],
            $fileData[JsonMigrationFields::PROPERTIES_TO_UPDATE],
            $fileData[JsonMigrationFields::VERSION]
        );
    }
}
