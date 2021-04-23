<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Cache;

/**
 * Inject configuration to the cache
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
interface CacheConfigurationManagerInterface
{
    /**
     * Inject to the FilesystemAdapter:
     * The namespace for caching items
     *
     * @see https://symfony.com/doc/current/components/cache/adapters/filesystem_adapter.html
     */
    public function getNamespace(): string;
    /**
     * Inject to the FilesystemAdapter:
     * The directory where to store the cache. If null, it uses the default /tmp system folder
     *
     * @see https://symfony.com/doc/current/components/cache/adapters/filesystem_adapter.html
     */
    public function getDirectory(): ?string;
}
