<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories;

use PoP\Root\Component\AbstractComponentConfiguration;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    // public function getPostCategoryListDefaultLimit(): ?int
    // {
    //     // Define properties
    //     $envVariable = Environment::POSTCATEGORY_LIST_DEFAULT_LIMIT;
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

    // public function getPostCategoryListMaxLimit(): ?int
    // {
    //     // Define properties
    //     $envVariable = Environment::POSTCATEGORY_LIST_MAX_LIMIT;
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

    public function getPostCategoriesRoute(): string
    {
        $envVariable = Environment::POSTCATEGORIES_ROUTE;
        $defaultValue = 'categories';

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }
}
