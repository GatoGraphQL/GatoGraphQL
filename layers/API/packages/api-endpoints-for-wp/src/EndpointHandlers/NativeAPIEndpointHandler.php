<?php

declare(strict_types=1);

namespace PoPAPI\APIEndpointsForWP\EndpointHandlers;

use PoP\Root\App;
use PoPAPI\API\Module as APIModule;
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
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getNativeAPIEndpoint();
    }

    /**
     * Check if the PoP API has been enabled
     */
    protected function isNativeAPIEnabled(): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return App::getModule(APIModule::class)->isEnabled()
            && !$moduleConfiguration->isNativeAPIEndpointDisabled();
    }
}
