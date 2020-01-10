<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration;

use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\Migration;
use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\MigrationParserInterface;
use Nette\Utils\FileSystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function sprintf;
use function time;

final class GenerateMigrationCommand extends Command
{
    /**
     * @var Configuration
     */
    private $migrationConfig;

    /**
     * @var MigrationParserInterface
     */
    private $migrationParser;


    public function __construct(Configuration $migrationConfig, MigrationParserInterface $migrationParser)
    {
        parent::__construct();
        $this->migrationConfig = $migrationConfig;
        $this->migrationParser = $migrationParser;
    }


    protected function configure(): void
    {
        $this->setName('elastic-search:migrations:generate');
        $this->addArgument('indexType', InputArgument::REQUIRED);
        $this->addArgument('mappingType', InputArgument::OPTIONAL, '', 'default');
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $indexType */
        $indexType = $input->getArgument('indexType');

        /** @var string $mappingType */
        $mappingType = $input->getArgument('mappingType');

        $migrationDefinition = new Migration($indexType, $mappingType, [], time());

        $fileName = sprintf(
            '%s/migration_%s_%s.json',
            $this->migrationConfig->getMigrationsDirectory(),
            $migrationDefinition->getIndexType(),
            $migrationDefinition->getVersion()
        );

        FileSystem::write($fileName, $this->migrationParser->objectToJson($migrationDefinition));

        return 0;
    }
}
