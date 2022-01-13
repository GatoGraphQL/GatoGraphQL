<?php

declare(strict_types=1);

namespace PoP\EngineWP;

use PoP\Engine\AppLoader;
use PoP\EngineWP\StateManagers\HookManager;
use PoP\Root\AbstractTestCase as UpstreamAbstractTestCase;
use PoP\Root\AppLoaderInterface;
use PoP\Root\StateManagers\HookManagerInterface;

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
