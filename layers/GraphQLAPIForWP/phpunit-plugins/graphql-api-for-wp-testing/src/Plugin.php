<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting;

use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Endpoints\AdminRESTAPIEndpointManager;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\Utilities\CustomHeaderAppender;

class Plugin
{
    public function initialize(): void
    {
        /**
         * Send custom headers needed for development
         */
        new CustomHeaderAppender();

        /**
         * Initialize REST endpoints
         */
        new AdminRESTAPIEndpointManager();
    }
}
