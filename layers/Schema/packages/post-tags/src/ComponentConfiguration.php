<?php

declare(strict_types=1);

namespace PoPSchema\PostTags;

use PoP\BasicService\Component\AbstractComponentConfiguration;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    // public function getPostTagListDefaultLimit(): ?int
    // {
    //     // Define properties
    //     $envVariable = Environment::POSTTAG_LIST_DEFAULT_LIMIT;
    //     $defaultValue = 10;
    //     $callback = [EnvironmentValueHelpers::class, 'toInt'];

    //     // Initialize property from the environment/hook
    //     $this->retrieveConfigurationValueOrUseDefault(
    //         $envVariable,
    //         $defaultValue,
    //         $callback
    //     );
    //     return $this->configuration[$envVariable];
    // }

    // public function getPostTagListMaxLimit(): ?int
    // {
    //     // Define properties
    //     $envVariable = Environment::POSTTAG_LIST_MAX_LIMIT;
    //     $defaultValue = -1; // Unlimited
    //     $callback = [EnvironmentValueHelpers::class, 'toInt'];

    //     // Initialize property from the environment/hook
    //     $this->retrieveConfigurationValueOrUseDefault(
    //         $envVariable,
    //         $defaultValue,
    //         $callback
    //     );
    //     return $this->configuration[$envVariable];
    // }

    public function getPostTagsRoute(): string
    {
        $envVariable = Environment::POSTTAGS_ROUTE;
        $defaultValue = 'tags';

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }
}
