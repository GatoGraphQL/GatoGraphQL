<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI;

use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Controllers\AbstractRESTController;

class AdminRESTAPIEndpointManager extends AbstractAdminRESTAPIEndpointManager
{
	/**
	 * @return AbstractRESTController[]
	 */
	protected function getControllers(): array
	{
		return [];
	}
}
