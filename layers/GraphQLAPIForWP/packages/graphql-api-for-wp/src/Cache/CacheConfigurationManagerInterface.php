<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Cache;

/**
 * Inject configuration to the cache
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
interface CacheConfigurationManagerInterface
{
    /**
     * Inject to the FilesystemAdapter:
     * A string used as the subdirectory of the root cache directory, where cache
     * items will be stored
     *
     * @see https://symfony.com/doc/current/components/cache/adapters/filesystem_adapter.html
     *
     * @return string
     */
    public function getNamespace(): string;
}
