<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Authentication;

use WP_Error;

use function add_action;
use function is_user_logged_in;
use function wp_create_nonce;

class RESTAuthentication
{
	protected ?string $restNonce = null;
	protected ?int $currentUserID = null;

	public function __construct() {
		add_action(
			'rest_authentication_errors',
			$this->retrieveLoggedInUserData(...),
			PHP_INT_MAX
		);
	}

	public function retrieveLoggedInUserData(?WP_Error $maybeError): ?WP_Error
	{
		if ($maybeError !== null) {
			return $maybeError;
		}

		if (is_user_logged_in()) {
			$this->restNonce = wp_create_nonce('wp_rest');
			$this->currentUserID = get_current_user_id();
		}

		return null;
	}

	public function getRESTNonce(): ?string
	{
		return $this->restNonce;
	}

	public function getCurrentUserID(): ?int
	{
		return $this->currentUserID;
	}
}