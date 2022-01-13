<?php

declare(strict_types=1);

namespace PoPSchema\GenericCustomPosts;

use PoP\Root\Component\AbstractComponentConfiguration;
use PoP\Root\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function getGenericCustomPostListDefaultLimit(): ?int
    {
        $envVariable = Environment::GENERIC_CUSTOMPOST_LIST_DEFAULT_LIMIT;
        $defaultValue = 10;
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function getGenericCustomPostListMaxLimit(): ?int
    {
        $envVariable = Environment::GENERIC_CUSTOMPOST_LIST_MAX_LIMIT;
        $defaultValue = -1; // Unlimited
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    /**
     * @return string[]
     */
    public function getGenericCustomPostTypes(): array
    {
        $envVariable = Environment::GENERIC_CUSTOMPOST_TYPES;
        $defaultValue = ['post'];
        $callback = [EnvironmentValueHelpers::class, 'commaSeparatedStringToArray'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
