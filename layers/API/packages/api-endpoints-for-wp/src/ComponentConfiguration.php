<?php

declare(strict_types=1);

namespace PoP\APIEndpointsForWP;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\APIEndpoints\EndpointUtils;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private bool $isNativeAPIEndpointDisabled = false;
    private string $getNativeAPIEndpoint = '/api/';

    public function isNativeAPIEndpointDisabled(): bool
    {
        // Define properties
        $envVariable = Environment::DISABLE_NATIVE_API_ENDPOINT;
        $selfProperty = &$this->isNativeAPIEndpointDisabled;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function getNativeAPIEndpoint(): string
    {
        // Define properties
        $envVariable = Environment::NATIVE_API_ENDPOINT;
        $selfProperty = &$this->getNativeAPIEndpoint;
        $defaultValue = '/api/';
        $callback = [EndpointUtils::class, 'slashURI'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }
}
