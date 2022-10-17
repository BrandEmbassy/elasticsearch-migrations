<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration;

use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\Migration;
use BrandEmbassy\ElasticSearchMigrations\Migration\Definition\MigrationSerializer;
use Nette\Utils\FileSystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function sprintf;
use function time;

/**
 * @final
 */
class GenerateMigrationCommand extends Command
{
    private Configuration $migrationConfig;

    private MigrationSerializer $migrationSerializer;


    public function __construct(Configuration $migrationConfig, MigrationSerializer $migrationSerializer)
    {
        parent::__construct();
        $this->migrationConfig = $migrationConfig;
        $this->migrationSerializer = $migrationSerializer;
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

        $migration = new Migration($mappingType, ['foo' => 'bar'], time());

        $fileName = sprintf(
            '%s/%s/migration_%s.json',
            $this->migrationConfig->getMigrationsDirectory(),
            $indexType,
            $migration->getVersion(),
        );

        FileSystem::write($fileName, $this->migrationSerializer->serialize($migration));

        return 0;
    }
}
