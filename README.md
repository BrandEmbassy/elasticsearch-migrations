# ElasticSearch mapping migrations

### Installation
```composer require brandembassy/elasticsearch-migrations```

### Usage

#### 1. Register services into your DI container 
```neon
    - BrandEmbassy\ElasticSearchMigrations\Migration\GenerateMigrationCommand
    - BrandEmbassy\ElasticSearchMigrations\Migration\Configuration('[pathToDirectoryWhereMigrationsAreStored]')
    - BrandEmbassy\ElasticSearchMigrations\Migration\Definition\Json\JsonMigrationParser
    - BrandEmbassy\ElasticSearchMigrations\Migration\Definition\Json\JsonMigrationSerializer
    - BrandEmbassy\ElasticSearchMigrations\Migration\Definition\DirectoryMigrationsLoader
    - BrandEmbassy\ElasticSearchMigrations\Index\Mapping\BasicIndexMappingPartialUpdaterFactory
    - BrandEmbassy\ElasticSearchMigrations\Migration\MigrationExecutor
```
#### 2. Generate new migration using symfony command
```php [yourConsoleAppScript] elastic-search:migrations:generate [indexType]```

#### 3. Set new index mapping in generated json 

Fill `propertiesToUpdate` field to define new mapping

```json
{
    "version": 1578674026,
    "mappingType": "default",
    "propertiesToUpdate": {
      "someRandomFoo": {
        "type": "keyword"
      }
    }
}
```

#### 4. Execute `MigrationsExecutor::migrate()`

For example using another symfony command
