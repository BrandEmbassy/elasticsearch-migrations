<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class LoadMigrationsCommand extends Command
{
    /**
     * @var MigrationsLoaderInterface
     */
    private $migrationsLoader;


    public function __construct(MigrationsLoaderInterface $migrationsLoader)
    {
        parent::__construct();
        $this->migrationsLoader = $migrationsLoader;
    }


    protected function configure(): void
    {
        $this->setName('elastic-search:migrations:load');
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $migrations = $this->migrationsLoader->loadMigrations();

        foreach ($migrations as $migration) {
            $output->writeln($migration->getVersion());
        }

        return 0;
    }
}
