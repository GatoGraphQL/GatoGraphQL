<?php

declare(strict_types=1);

namespace PoP\Engine\Testing\PHPUnit;

use PoP\Engine\AppLoader;
use PoP\Root\Testing\PHPUnit\AbstractIntegrationTestCase as UpstreamAbstractIntegrationTestCase;

abstract class AbstractIntegrationTestCase extends UpstreamAbstractIntegrationTestCase
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
        AppLoader::bootApplication($cacheContainerConfiguration, $containerNamespace, $containerDirectory, $isDev);
    }
}
