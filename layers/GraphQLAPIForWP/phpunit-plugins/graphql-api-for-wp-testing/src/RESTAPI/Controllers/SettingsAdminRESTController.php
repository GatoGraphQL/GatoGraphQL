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
	 * @return array<string,array<string,mixed>> Array of [$route => $options]
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
			$settingsName = $params['name'] ?? null;
			if ($settingsName === null) {
				throw new Exception(
					__('The settings name has not been provided', 'graphql-api')
				);
			}
			if (!array_key_exists('value', $params)) {
				throw new Exception(
					__('The value has not been provided', 'graphql-api')
				);
			}
			$settingsValue = (bool) $params['value'];

			// @todo Remove this temporary code
			$response->data->settingsName = $settingsName;
			$response->data->settingsValue = $settingsValue;

			// Success!
			$response->status = ResponseStatus::SUCCESS;
			$response->message = sprintf(
				__('The settings for \'%s\' have been updated successfully', 'graphql-api'),
				$settingsName
			);
		} catch ( Exception $e ) {
			$response->status = ResponseStatus::ERROR;
			$response->message = $e->getMessage();
		}

		return rest_ensure_response($response);
	}	
}
