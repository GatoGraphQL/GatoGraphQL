<?php

declare(strict_types=1);

namespace PoP\RootWP;

use PoP\Root\AbstractTestCaseCase as UpstreamAbstractTestCaseCase;
use PoP\Root\AppLoaderInterface;
use PoP\Root\StateManagers\HookManagerInterface;
use PoP\RootWP\StateManagers\HookManager;

abstract class AbstractWordPressTestCaseCase extends UpstreamAbstractTestCaseCase
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
