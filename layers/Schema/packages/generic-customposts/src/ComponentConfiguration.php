<?php

declare(strict_types=1);

namespace PoPSchema\GenericCustomPosts;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function getGenericCustomPostListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::GENERIC_CUSTOMPOST_LIST_DEFAULT_LIMIT;
        $defaultValue = 10;
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        // Initialize property from the environment/hook
        $this->getConfigurationValueFromEnvVariable(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    public function getGenericCustomPostListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::GENERIC_CUSTOMPOST_LIST_MAX_LIMIT;
        $defaultValue = -1; // Unlimited
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        // Initialize property from the environment/hook
        $this->getConfigurationValueFromEnvVariable(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    /**
     * @return string[]
     */
    public function getGenericCustomPostTypes(): array
    {
        // Define properties
        $envVariable = Environment::GENERIC_CUSTOMPOST_TYPES;
        $defaultValue = ['post'];
        $callback = [EnvironmentValueHelpers::class, 'commaSeparatedStringToArray'];

        // Initialize property from the environment/hook
        $this->getConfigurationValueFromEnvVariable(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }
}
