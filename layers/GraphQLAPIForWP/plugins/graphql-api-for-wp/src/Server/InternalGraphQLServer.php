<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Server;

use GraphQLAPI\GraphQLAPI\AppThread;
use GraphQLAPI\GraphQLAPI\PluginAppGraphQLServerNames;
use GraphQLAPI\GraphQLAPI\PluginAppHooks;
use GraphQLByPoP\GraphQLServer\Server\AbstractAttachedGraphQLServer;
use PoP\ComponentModel\App;
use PoP\ComponentModel\AppThreadInterface;
use PoP\RootWP\StateManagers\HookManager;
use PoP\Root\StateManagers\HookManagerInterface;

use function do_action;

class InternalGraphQLServer extends AbstractAttachedGraphQLServer
{
    protected function createAppThread(): AppThreadInterface
    {
        return new AppThread();
    }

    // protected function getHookManager(): HookManagerInterface
    // {
    //     return new HookManager();
    // }

    protected function initializeApp(): AppThreadInterface
    {
        do_action(
            PluginAppHooks::INITIALIZE_APP,
            PluginAppGraphQLServerNames::INTERNAL,
        );
        return App::getAppThread();
    }
}
