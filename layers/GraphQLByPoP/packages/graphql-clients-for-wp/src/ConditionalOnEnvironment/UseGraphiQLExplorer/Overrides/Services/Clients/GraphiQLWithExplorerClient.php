<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLClientsForWP\ConditionalOnEnvironment\UseGraphiQLExplorer\Overrides\Services\Clients;

use GraphQLByPoP\GraphQLClientsForWP\Clients\AbstractGraphiQLClient;

class GraphiQLWithExplorerClient extends AbstractGraphiQLClient
{
    protected function getClientRelativePath(): string
    {
        // This is where the files are generated when running `npm run build`
        return '/clients/graphiql-with-explorer/build';
    }
    protected function getJSFilename(): string
    {
        return 'main.js';
    }
    /**
     * Assets folder name
     */
    protected function getAssetDirname(): string
    {
        // Please notice! GraphiQL Explorer loads under "/assets...",
        // that's why the dirname starts with "/"
        return '/static';
    }
}
