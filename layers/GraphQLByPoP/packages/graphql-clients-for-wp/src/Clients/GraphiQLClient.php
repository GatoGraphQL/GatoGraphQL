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
}
