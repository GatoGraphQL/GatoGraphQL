<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

class PluginAppGraphQLServerNames
{
    /**
     * GraphQL server that processes the request
     */
    public final const STANDARD = 'standard';
    /**
     * GraphQL server that processes internal executions
     */
    public final const INTERNAL = 'internal';
}
