<?php

declare(strict_types=1);

namespace PoP\Engine\Testing\PHPUnit;

use PoP\Engine\AppLoader;
use PoP\Root\Testing\PHPUnit\AbstractTestCase as UpstreamAbstractTestCase;

abstract class AbstractTestCase extends UpstreamAbstractTestCase
{
    protected static function initializeAppLoader(
        string $componentClass,
        ?bool $cacheContainerConfiguration = null,
        ?string $containerNamespace = null,
        ?string $containerDirectory = null,
        bool $isDev = false
    ): void {
        AppLoader::addComponentClassesToInitialize([$componentClass]);
        AppLoader::bootSystem($cacheContainerConfiguration, $containerNamespace, $containerDirectory, $isDev);
        
        // Only after initializing the System Container,
        // we can obtain the configuration (which may depend on hooks)
        AppLoader::addComponentClassConfiguration(
            static::getComponentClassConfiguration()
        );
        
        AppLoader::bootApplication($cacheContainerConfiguration, $containerNamespace, $containerDirectory, $isDev);
    }
}
