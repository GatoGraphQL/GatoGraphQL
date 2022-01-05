<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories;

use PoP\BasicService\Component\AbstractComponentConfiguration;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private string $getPostCategoriesRoute = '';

    // public function getPostCategoryListDefaultLimit(): ?int
    // {
    //     // Define properties
    //     $envVariable = Environment::POSTCATEGORY_LIST_DEFAULT_LIMIT;
    //     $defaultValue = 10;
    //     $callback = [EnvironmentValueHelpers::class, 'toInt'];

    //     // Initialize property from the environment/hook
    //     $this->maybeInitializeConfigurationValue(
    //         $envVariable,
    //         $defaultValue,
    //         $callback
    //     );
    //     return $this->configuration[$envVariable];
    // }

    // public function getPostCategoryListMaxLimit(): ?int
    // {
    //     // Define properties
    //     $envVariable = Environment::POSTCATEGORY_LIST_MAX_LIMIT;
    //     $defaultValue = -1; // Unlimited
    //     $callback = [EnvironmentValueHelpers::class, 'toInt'];

    //     // Initialize property from the environment/hook
    //     $this->maybeInitializeConfigurationValue(
    //         $envVariable,
    //         $defaultValue,
    //         $callback
    //     );
    //     return $this->configuration[$envVariable];
    // }

    public function getPostCategoriesRoute(): string
    {
        // Define properties
        $envVariable = Environment::POSTCATEGORIES_ROUTE;
        $defaultValue = 'categories';

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
        );
        return $this->configuration[$envVariable];
    }
}
