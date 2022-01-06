<?php

declare(strict_types=1);

namespace PoP\APIEndpointsForWP;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\APIEndpoints\EndpointUtils;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function isNativeAPIEndpointDisabled(): bool
    {
        // Define properties
        $envVariable = Environment::DISABLE_NATIVE_API_ENDPOINT;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->getConfigurationValueFromEnvVariable(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    public function getNativeAPIEndpoint(): string
    {
        // Define properties
        $envVariable = Environment::NATIVE_API_ENDPOINT;
        $defaultValue = '/api/';
        $callback = [EndpointUtils::class, 'slashURI'];

        // Initialize property from the environment/hook
        $this->getConfigurationValueFromEnvVariable(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }
}
