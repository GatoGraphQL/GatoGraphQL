<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI;

use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Constants\ResponseStatus;
use stdClass;

class RESTResponse
{
	public string $status = ResponseStatus::ERROR;
	public string $message = '';
	/**
	 * Extra data
	 */
	public stdClass $data;

	public function __construct() {
		$this->data = new stdClass();
	}
}
