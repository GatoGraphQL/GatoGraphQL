<?php

declare(strict_types=1);

namespace PoP\RootWP;

use PoP\Root\AbstractTestCase as UpstreamAbstractTestCase;
use PoP\Root\AppLoaderInterface;
use PoP\Root\StateManagers\HookManagerInterface;
use PoP\RootWP\StateManagers\HookManager;

abstract class AbstractTestCase extends UpstreamAbstractTestCase
{
    protected static function getAppLoader(): AppLoaderInterface
    {
        return new AppLoader();
    }

    protected static function getHookManager(): HookManagerInterface
    {
        return new HookManager();
    }
}
