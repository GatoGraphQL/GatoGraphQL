<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Endpoints;

use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Controllers\AbstractRESTController;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Controllers\SettingsAdminRESTController;

class AdminRESTAPIEndpointManager extends AbstractRESTAPIEndpointManager
{
	/**
	 * @return AbstractRESTController[]
	 */
	protected function getControllers(): array
	{
		return [
			new SettingsAdminRESTController(),
		];
	}
}
