<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Standalone;

use GraphQLByPoP\GraphQLServer\Standalone\GraphQLServer as UpstreamGraphQLServer;
use PoP\RootWP\StateManagers\HookManager;
use PoP\Root\StateManagers\HookManagerInterface;

class GraphQLServer extends UpstreamGraphQLServer
{
    /**
     * Comment: Do NOT use the WP AppLoader,
     * because it executes `bootApplicationModules()`
     * via hooks, and these may have been executed
     * by then, then it'll throw errors.
     */
    // protected function getAppLoader(): AppLoaderInterface
    // {
    //     return new \PoP\RootWP\AppLoader();
    // }

    protected function getHookManager(): HookManagerInterface
    {
        return new HookManager();
    }
}
