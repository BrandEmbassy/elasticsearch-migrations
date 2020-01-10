# ElasticSearch mapping migrations

### Installation
```composer require brandembassy/elasticsearch-migrations```

### Usage

#### 1. Register services into your DI container 
```neon
    elasticSearchGenerateMigrationCommand: BrandEmbassy\ElasticSearchMigrations\Migration\GenerateMigrationCommand
    - BrandEmbassy\ElasticSearchMigrations\Migration\Configuration('[pathToDirectoryWhereMigrationsAreStored]')
    - BrandEmbassy\ElasticSearchMigrations\Migration\Definition\MigrationParser
    - BrandEmbassy\ElasticSearchMigrations\Migration\Definition\MigrationsLoader
    - BrandEmbassy\ElasticSearchMigrations\Migration\MigrationExecutor
```
#### 2. Generate new migration using symfony command
```php [yourConsoleAppScript] elastic-search:migrations:generate [indexType]```

#### 3. Set new index mapping in generated json 

Set up `propertiesToUpdate` field to define new mapping

```json
{
    "version": 1578674026,
    "indexType": "page_views",
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
