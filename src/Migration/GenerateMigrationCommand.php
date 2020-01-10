<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration;

use Nette\Utils\FileSystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function sprintf;

final class GenerateMigrationCommand extends Command
{
    /**
     * @var MigrationConfig
     */
    private $migrationConfig;

    /**
     * @var MigrationParserInterface
     */
    private $migrationParser;


    public function __construct(MigrationConfig $migrationConfig, MigrationParserInterface $migrationParser)
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
        $indexType = $input->getArgument('indexType');
        $mappingType = $input->getArgument('mappingType');
        $version = time();

        $migrationDefinition = new MigrationDefinition($indexType, $mappingType, [], $version);

        $fileName = sprintf(
            '%s/migration_%s_%s.json',
            $this->migrationConfig->getMigrationsDirectory(),
            $migrationDefinition->getIndexType(),
            $version
        );
        FileSystem::write($fileName, $this->migrationParser->objectToJson($migrationDefinition));

        return 0;
    }
}
