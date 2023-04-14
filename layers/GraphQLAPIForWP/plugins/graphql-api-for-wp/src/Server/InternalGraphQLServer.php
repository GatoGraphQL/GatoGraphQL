<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Server;

use GraphQLAPI\GraphQLAPI\AppThread;
use GraphQLByPoP\GraphQLServer\Server\AbstractAttachedGraphQLServer;
use PoP\ComponentModel\AppThreadInterface;
use PoP\Root\StateManagers\HookManagerInterface;
use PoP\RootWP\StateManagers\HookManager;

class InternalGraphQLServer extends AbstractAttachedGraphQLServer
{
    protected function createAppThread(): AppThreadInterface
    {
        return new AppThread();
    }

    protected function getHookManager(): HookManagerInterface
    {
        return new HookManager();
    }
}
