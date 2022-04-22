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
		$routeOptions = $this->getRouteOptions();
		if ($routeOptions === []) {
			return;
		}

		foreach ($routeOptions as $route => $routeOptions) {
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
			$routeBase = $this->getRouteBase();
			if ($routeBase !== '') {
				$route = sprintf(
					'%s/%s',
					$routeBase,
					$route
				);
			}
			register_rest_route(
				$namespace,
				$route,
				$routeOptions
			);
		}
	}

	/**
	 * @return array<string,array<array<string,mixed>>> Array of [$route => $options]
	 */
	abstract protected function getRouteOptions(): array;

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

	protected function getRouteBase(): string
	{
		return '';
	}

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
