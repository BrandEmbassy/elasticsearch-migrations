<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @final
 */
class LastMigrationVersionCommand extends Command
{
    private MigrationFinder $migrationFinder;


    public function __construct(MigrationFinder $migrationFinder)
    {
        parent::__construct();
        $this->migrationFinder = $migrationFinder;
    }


    protected function configure(): void
    {
        $this->setName('elastic-search:migrations:last-migration');
        $this->addArgument('indexType', InputArgument::REQUIRED);
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $indexType */
        $indexType = $input->getArgument('indexType');

        $lastMigration = $this->migrationFinder->findLastMigration($indexType);

        $output->writeln($lastMigration === null ? 'migrations not found' : (string)$lastMigration->getVersion());

        return 0;
    }
}
