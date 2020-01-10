<?php declare(strict_types = 1);

namespace BrandEmbassy\ElasticSearchMigrations\Client;

use Exception;

final class MissingConnectionException extends Exception
{
    public static function create(): self
    {
        return new self('Can\'t establish ElasticSearch connection');
    }
}
