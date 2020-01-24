<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Migration\Definition;

use Doctrine\Common\Collections\Collection;

interface MigrationsLoader
{
    /**
     * @return Collection<int, Migration>|Migration[]
     */
    public function loadMigrations(string $indexType): Collection;
}
