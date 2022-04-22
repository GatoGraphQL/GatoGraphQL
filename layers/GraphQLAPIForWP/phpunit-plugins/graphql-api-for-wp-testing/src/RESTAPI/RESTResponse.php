<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI;

use stdClass;

class RESTResponse
{
    public function __construct(
        public string $status = '',
        public string $message = '',
        /**
         * Extra data
         */
        public stdClass $data = new stdClass(),
    ) {
    }
}
