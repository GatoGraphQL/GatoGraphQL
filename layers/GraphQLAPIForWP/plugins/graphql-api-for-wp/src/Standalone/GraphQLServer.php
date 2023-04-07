<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Standalone;

use GraphQLByPoP\GraphQLServer\Standalone\GraphQLServer as UpstreamGraphQLServer;
use PoP\Root\StateManagers\HookManagerInterface;
use PoP\RootWP\StateManagers\HookManager;

class GraphQLServer extends UpstreamGraphQLServer
{
    protected function getHookManager(): HookManagerInterface
    {
        return new HookManager();
    }
}
