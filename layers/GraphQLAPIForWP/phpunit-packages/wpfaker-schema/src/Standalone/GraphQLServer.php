<?php

declare(strict_types=1);

namespace GraphQLAPI\WPFakerSchema\Standalone;

use GraphQLAPI\WPFakerSchema\App;
use GraphQLByPoP\GraphQLServer\Standalone\GraphQLServer as UpstreamGraphQLServer;

class GraphQLServer extends UpstreamGraphQLServer
{
    protected function initializeApp(): void
    {
        parent::initializeApp();
        App::initializeMockDataStore();
    }
}
