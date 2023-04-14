<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Server;

use GraphQLByPoP\GraphQLServer\Server\AbstractGraphQLServer;
use PoP\RootWP\StateManagers\HookManager;
use PoP\Root\StateManagers\HookManagerInterface;

class InternalGraphQLServer extends AbstractGraphQLServer
{
    protected function getHookManager(): HookManagerInterface
    {
        return new HookManager();
    }
}
