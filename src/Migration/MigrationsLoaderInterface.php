<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration;

use Doctrine\Common\Collections\Collection;

interface MigrationsLoaderInterface
{
    /**
     * @return Collection|MigrationDefinitionInterface[]
     */
    public function loadMigrations(): Collection;
}
