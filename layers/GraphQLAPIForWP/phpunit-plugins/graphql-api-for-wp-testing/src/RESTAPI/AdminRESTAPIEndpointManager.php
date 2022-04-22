<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI;

use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Controllers\AbstractRESTController;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Controllers\SettingsAdminRESTController;

class AdminRESTAPIEndpointManager extends AbstractAdminRESTAPIEndpointManager
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
