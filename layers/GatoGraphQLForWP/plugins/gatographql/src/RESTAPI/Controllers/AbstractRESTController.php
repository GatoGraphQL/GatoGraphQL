<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\RESTAPI\Controllers;

use GatoGraphQL\GatoGraphQL\PluginApp;
use WP_REST_Controller;

use function register_rest_route;

/**
 * Base class to register WordPress REST routes, integrated with the
 * Gato GraphQL service container (controllers are collected via a
 * CompilerPass and their routes registered by the endpoint manager).
 *
 * The REST namespace is built once here: `{pluginNamespace}/{version}`
 * (e.g. `gatographql/v1`), optionally suffixed with a controller
 * namespace.
 */
abstract class AbstractRESTController extends WP_REST_Controller implements RESTControllerInterface
{
    public function registerRoutes(): void
    {
        $this->register_routes();
    }

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
        foreach ($routeOptions as $route => $options) {
            register_rest_route(
                $namespace,
                $route,
                $options
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
     * @return array<string,array<string,mixed>|array<array<string,mixed>>> Array of [$route => $options]
     */
    abstract protected function getRouteOptions(): array;

    protected function getPluginNamespace(): string
    {
        return PluginApp::getMainPlugin()->getPluginNamespace();
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
