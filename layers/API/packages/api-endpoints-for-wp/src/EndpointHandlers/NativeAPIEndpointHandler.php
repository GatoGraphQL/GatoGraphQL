<?php

declare(strict_types=1);

namespace PoPAPI\APIEndpointsForWP\EndpointHandlers;

use PoP\Root\App;
use PoPAPI\API\Module as APIComponent;
use PoPAPI\APIEndpointsForWP\Module;
use PoPAPI\APIEndpointsForWP\ModuleConfiguration;

class NativeAPIEndpointHandler extends AbstractEndpointHandler
{
    /**
     * Initialize the endpoints
     */
    public function initialize(): void
    {
        if ($this->isNativeAPIEnabled()) {
            parent::initialize();
        }
    }

    /**
     * Provide the endpoint
     */
    public function getEndpoint(): string
    {
        /** @var ModuleConfiguration */
        $componentConfiguration = App::getComponent(Module::class)->getConfiguration();
        return $componentConfiguration->getNativeAPIEndpoint();
    }

    /**
     * Check if the PoP API has been enabled
     */
    protected function isNativeAPIEnabled(): bool
    {
        /** @var ModuleConfiguration */
        $componentConfiguration = App::getComponent(Module::class)->getConfiguration();
        return App::getComponent(APIComponent::class)->isEnabled()
            && !$componentConfiguration->isNativeAPIEndpointDisabled();
    }
}
