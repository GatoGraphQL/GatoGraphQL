<?php

declare(strict_types=1);

namespace PoP\APIEndpointsForWP;

use PoP\Root\Component\AbstractComponentConfiguration;
use PoP\APIEndpoints\EndpointUtils;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function isNativeAPIEndpointDisabled(): bool
    {
        $envVariable = Environment::DISABLE_NATIVE_API_ENDPOINT;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function getNativeAPIEndpoint(): string
    {
        $envVariable = Environment::NATIVE_API_ENDPOINT;
        $defaultValue = '/api/';
        $callback = [EndpointUtils::class, 'slashURI'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
