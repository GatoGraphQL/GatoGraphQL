<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Controllers;

use Exception;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Constants\ResponseStatus;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\RESTResponse;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

use function esc_html__;
use function rest_ensure_response;

class SettingsAdminRESTController extends AbstractAdminRESTController
{
	/**
	 * @return array<string,array<array<string,mixed>>> Array of [$route => $options]
	 */
	protected function getRouteOptions(): array
	{
		return [
			'settings' => [
				'methods' => WP_REST_Server::CREATABLE,
				'callback' => $this->updateSettings(...),
				'permission_callback' => $this->checkAdminPermission(...),
			],
		];
	}

	public function updateSettings(WP_REST_Request $request): WP_REST_Response|WP_Error
	{
		$response = new RESTResponse();

		try {
			$params = $request->get_params();
			$settingsName = $params['settingsName'] ?? null;
			if ($settingsName === null) {
				throw new Exception(
					esc_html__('The settings name has not been provided', 'graphql-api')
				);
			}

			$response->data->songa = 'Danga';
			$response->status = ResponseStatus::SUCCESS;
		} catch ( Exception $e ) {
			$response->message = $e->getMessage();
		}

		return rest_ensure_response($response);
	}	
}
