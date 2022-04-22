<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI;

use function current_user_can;

abstract class AbstractAdminRESTAPIEndpointManager extends AbstractRESTAPIEndpointManager
{
	public function __construct()
	{
		if (!current_user_can('administrator')) {
			return;
		}

		parent::__construct();
	}
}
