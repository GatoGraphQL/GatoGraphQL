<?php

declare(strict_types=1);

namespace PoPSchema\PostTags;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    // private ?int $getPostTagListDefaultLimit = 10;
    // private ?int $getPostTagListMaxLimit = -1;
    private string $getPostTagsRoute = '';

    // public function getPostTagListDefaultLimit(): ?int
    // {
    //     // Define properties
    //     $envVariable = Environment::POSTTAG_LIST_DEFAULT_LIMIT;
    //     $selfProperty = &$this->getPostTagListDefaultLimit;
    //     $defaultValue = 10;
    //     $callback = [EnvironmentValueHelpers::class, 'toInt'];

    //     // Initialize property from the environment/hook
    //     $this->maybeInitializeConfigurationValue(
    //         $envVariable,
    //         $selfProperty,
    //         $defaultValue,
    //         $callback
    //     );
    //     return $selfProperty;
    // }

    // public function getPostTagListMaxLimit(): ?int
    // {
    //     // Define properties
    //     $envVariable = Environment::POSTTAG_LIST_MAX_LIMIT;
    //     $selfProperty = &$this->getPostTagListMaxLimit;
    //     $defaultValue = -1; // Unlimited
    //     $callback = [EnvironmentValueHelpers::class, 'toInt'];

    //     // Initialize property from the environment/hook
    //     $this->maybeInitializeConfigurationValue(
    //         $envVariable,
    //         $selfProperty,
    //         $defaultValue,
    //         $callback
    //     );
    //     return $selfProperty;
    // }

    public function getPostTagsRoute(): string
    {
        // Define properties
        $envVariable = Environment::POSTTAGS_ROUTE;
        $selfProperty = &$this->getPostTagsRoute;
        $defaultValue = 'tags';

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue
        );
        return $selfProperty;
    }
}
