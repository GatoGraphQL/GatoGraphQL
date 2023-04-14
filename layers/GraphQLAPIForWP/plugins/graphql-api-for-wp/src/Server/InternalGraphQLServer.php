<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Server;

use GraphQLAPI\GraphQLAPI\AppThread;
use GraphQLAPI\GraphQLAPI\PluginAppHooks;
use GraphQLByPoP\GraphQLServer\Server\AbstractAttachedGraphQLServer;
use PoP\ComponentModel\AppThreadInterface;
use PoP\Root\StateManagers\HookManagerInterface;
use PoP\RootWP\StateManagers\HookManager;

use function do_action;

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

    protected function initializeApp(): void
    {
        do_action(PluginAppHooks::INITIALIZE_APP);
    }
}
