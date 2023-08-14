<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Endpoints;

use PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Controllers\AbstractRESTController;

use function add_action;

abstract class AbstractRESTAPIEndpointManager
{
    public function __construct()
    {
        $this->initialize();
    }

    public function initialize(): void
    {
        if (!class_exists('WP_REST_Server')) {
            return;
        }

        add_action('rest_api_init', $this->registerRoutes(...));
    }

    public function registerRoutes(): void
    {
        foreach ($this->getControllers() as $controller) {
            $controller->register_routes();
        }
    }

    /**
     * @return AbstractRESTController[]
     */
    abstract protected function getControllers(): array;
}
