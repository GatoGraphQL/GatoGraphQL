<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Controllers;

use WP_REST_Controller;
use WP_REST_Response;
use WP_Error;

use function register_rest_route;
use function add_filter;
use function rest_ensure_response;

abstract class AbstractRESTController extends WP_REST_Controller
{
	/**
	 * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
	 */
	public function register_routes(): void
	{
		$routeNameOptions = $this->getRouteNameOptions();
		if ($routeNameOptions === []) {
			return;
		}

		foreach ($routeNameOptions as $name => $options) {
			$namespace = sprintf(
				'%s/%s',
				$this->getPluginNamespace(),
				$this->getVersion(),
			);
			$controllerNamespace = $this->getControllerNamespace();
			if ($controllerNamespace !== '') {
				$namespace = sprintf(
					'%s/%s',
					$namespace,
					$controllerNamespace
				);
			}
			$restBase = sprintf(
				'/%s/%s',
				$this->getRESTBase(),
				$name
			);
			register_rest_route(
				$namespace,
				$restBase,
				$options
			);
		}
	}

	/**
	 * @return array<string,array<array<string,mixed>>>
	 */
	abstract protected function getRouteNameOptions(): array;

	final protected function getPluginNamespace(): string
	{
		return 'graphql-api';
	}

	protected function getVersion(): string
	{
		return 'v1';
	}

	protected function getControllerNamespace(): string
	{
		return '';
	}

	abstract protected function getRESTBase(): string;

	public function ensureResponse(array $data): WP_REST_Response|WP_Error
	{
		add_filter(
			'rest_pre_serve_request',
			fn (bool $served, WP_REST_Response $result) => $this->printResponse($result),
			10,
			2
		);
		return rest_ensure_response($data);
	}

	public function printResponse(WP_REST_Response $result): never
	{
		echo wp_json_encode( $result->get_data() );
		die;
	}
}
