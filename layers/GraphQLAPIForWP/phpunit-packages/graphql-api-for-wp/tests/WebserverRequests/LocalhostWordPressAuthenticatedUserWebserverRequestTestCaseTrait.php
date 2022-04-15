<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\WebserverRequests;

use PHPUnitForGraphQLAPI\WebserverRequests\WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

trait LocalhostWordPressAuthenticatedUserWebserverRequestTestCaseTrait
{
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;
    
    protected static function getLoginUsername(): string
    {
        return 'admin';
    }

    protected static function getLoginPassword(): string
    {
        return 'admins';
    }
}
