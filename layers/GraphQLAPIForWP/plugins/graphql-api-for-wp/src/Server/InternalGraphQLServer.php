<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Server;

use GatoGraphQL\GatoGraphQL\PluginAppGraphQLServerNames;
use GatoGraphQL\GatoGraphQL\PluginAppHooks;
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
