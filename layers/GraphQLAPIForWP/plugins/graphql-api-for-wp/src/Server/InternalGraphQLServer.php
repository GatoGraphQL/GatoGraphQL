<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Server;

use GraphQLAPI\GraphQLAPI\PluginAppGraphQLServerNames;
use GraphQLAPI\GraphQLAPI\PluginAppHooks;
use GraphQLByPoP\GraphQLServer\Server\AbstractAttachedGraphQLServer;
use PoP\ComponentModel\App;
use PoP\Root\AppThreadInterface;

use function do_action;

class InternalGraphQLServer extends AbstractAttachedGraphQLServer
{
    protected function initializeApp(): AppThreadInterface
    {
        do_action(
            PluginAppHooks::INITIALIZE_APP,
            PluginAppGraphQLServerNames::INTERNAL,
        );
        return App::getAppThread();
    }
}
