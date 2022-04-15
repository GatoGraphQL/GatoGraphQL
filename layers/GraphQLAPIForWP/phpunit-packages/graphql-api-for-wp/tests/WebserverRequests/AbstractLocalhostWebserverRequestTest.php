<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI;

use PHPUnitForGraphQLAPI\WebserverRequests\AbstractWebserverRequestTestCase;

abstract class AbstractLocalhostWebserverRequestTest extends AbstractWebserverRequestTestCase
{
    protected static function getWebserverDomain(): string
    {
        return 'graphql-api.lndo.site';
    }
}
