<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLClientsForWP\Clients;

class GraphiQLClient extends AbstractGraphiQLClient
{
    protected function getClientRelativePath(): string
    {
        return '/clients/graphiql';
    }
    protected function getJSFilename(): string
    {
        return 'graphiql.js';
    }

    /**
     * Check if GraphiQL Explorer must be enabled or not
     */
    protected function matchesGraphiQLExplorerRequiredState(bool $useGraphiQLExplorer): bool
    {
        return !$useGraphiQLExplorer;
    }
}
