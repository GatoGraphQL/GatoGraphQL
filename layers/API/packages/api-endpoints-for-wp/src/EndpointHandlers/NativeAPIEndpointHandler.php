<?php

declare(strict_types=1);

namespace PoP\APIEndpointsForWP\EndpointHandlers;

use PoP\API\Component as APIComponent;
use PoP\API\Response\Schemes as APISchemes;
use PoP\APIEndpointsForWP\Component;
use PoP\APIEndpointsForWP\ComponentConfiguration;
use PoP\ComponentModel\Constants\Params;

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
    protected function getEndpoint(): string
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = \PoP\Root\Managers\ComponentManager::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getNativeAPIEndpoint();
    }

    /**
     * Check if the PoP API has been enabled
     */
    protected function isNativeAPIEnabled(): bool
    {
        return
            \PoP\Root\Managers\ComponentManager::getComponent(APIComponent::class)->isEnabled()
            && !ComponentConfiguration::isNativeAPIEndpointDisabled();
    }

    /**
     * Indicate this is an API request
     */
    protected function executeEndpoint(): void
    {
        // Set the params on the request, to emulate that they were added by the user
        $_REQUEST[Params::SCHEME] = APISchemes::API;
        // Enable hooks
        \do_action('EndpointHandler:setDoingAPI');
    }
}
