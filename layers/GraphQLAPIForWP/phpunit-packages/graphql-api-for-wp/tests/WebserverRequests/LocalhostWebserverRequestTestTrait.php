<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\WebserverRequests;

trait LocalhostWebserverRequestTestTrait
{
    protected static function getWebserverDomain(): string
    {
        return 'graphql-api.lndo.site';
    }
}
