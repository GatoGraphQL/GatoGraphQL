<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\RESTAPI\Endpoints;

use GatoGraphQL\GatoGraphQL\RESTAPI\Registries\RESTControllerRegistryInterface;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

use function add_action;

/**
 * On `rest_api_init`, registers the WordPress REST routes for every
 * REST controller collected into the registry (via the CompilerPass).
 */
class RESTAPIEndpointManager extends AbstractAutomaticallyInstantiatedService implements RESTAPIEndpointManagerInterface
{
    private ?RESTControllerRegistryInterface $restControllerRegistry = null;

    final protected function getRESTControllerRegistry(): RESTControllerRegistryInterface
    {
        if ($this->restControllerRegistry === null) {
            /** @var RESTControllerRegistryInterface */
            $restControllerRegistry = $this->instanceManager->getInstance(RESTControllerRegistryInterface::class);
            $this->restControllerRegistry = $restControllerRegistry;
        }
        return $this->restControllerRegistry;
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
        foreach ($this->getRESTControllerRegistry()->getRESTControllers() as $restController) {
            $restController->registerRoutes();
        }
    }
}
