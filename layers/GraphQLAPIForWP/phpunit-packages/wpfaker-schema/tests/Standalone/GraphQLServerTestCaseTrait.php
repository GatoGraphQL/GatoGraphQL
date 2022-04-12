<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WPFakerSchema\Standalone;

use GraphQLAPI\WPFakerSchema\Standalone\GraphQLServer;
use GraphQLByPoP\GraphQLServer\Standalone\GraphQLServer as UpstreamGraphQLServer;

trait GraphQLServerTestCaseTrait
{
    protected static function createGraphQLServer(): UpstreamGraphQLServer
    {
        return new GraphQLServer(
            static::getGraphQLServerComponentClasses(),
            static::getGraphQLServerComponentClassConfiguration()
        );
    }
}
