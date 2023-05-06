<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Controllers;

use WP_REST_Controller;

use function register_rest_route;

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

        $namespace = $this->getNamespace();
        foreach ($routeOptions as $route => $routeOptions) {
            register_rest_route(
                $namespace,
                $route,
                $routeOptions
            );
        }
    }

    final protected function getNamespace(): string
    {
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
        return $namespace;
    }

    /**
     * @return array<string,array<array<string,mixed>>> Array of [$route => [$options]]
     */
    abstract protected function getRouteOptions(): array;

    final protected function getPluginNamespace(): string
    {
        return 'gato-graphql';
    }

    protected function getVersion(): string
    {
        return 'v1';
    }

    protected function getControllerNamespace(): string
    {
        return '';
    }
}
