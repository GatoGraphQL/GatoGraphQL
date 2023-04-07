<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Standalone;

use GraphQLByPoP\GraphQLServer\Standalone\GraphQLServer as UpstreamGraphQLServer;
use PoP\RootWP\AppLoader;
use PoP\RootWP\StateManagers\HookManager;
use PoP\Root\AppLoaderInterface;
use PoP\Root\StateManagers\HookManagerInterface;

class GraphQLServer extends UpstreamGraphQLServer
{
    protected function getAppLoader(): AppLoaderInterface
    {
        return new AppLoader();
    }

    protected function getHookManager(): HookManagerInterface
    {
        return new HookManager();
    }
}
