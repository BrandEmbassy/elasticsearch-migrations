<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration\Definition;

use Doctrine\Common\Collections\Collection;

interface MigrationsLoaderInterface
{
    /**
     * @return Collection<int, MigrationInterface>|MigrationInterface[]
     */
    public function loadMigrations(): Collection;
}
