<?php

declare(strict_types=1);

namespace PoP\APIEndpointsForWP\EndpointHandlers;

use PoP\API\Component;
use PoP\ComponentModel\Constants\Params;
use PoP\APIEndpointsForWP\EndpointHandlers\AbstractEndpointHandler;
use PoP\APIEndpointsForWP\ComponentConfiguration;
use PoP\API\Response\Schemes as APISchemes;

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
     *
     * @var string
     */
    protected function getEndpoint(): string
    {
        return ComponentConfiguration::getNativeAPIEndpoint();
    }

    /**
     * Check if the PoP API has been enabled
     */
    protected function isNativeAPIEnabled(): bool
    {
        return
            Component::isEnabled()
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
