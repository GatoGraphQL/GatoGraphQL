<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories;

use PoP\Root\Module\AbstractModuleConfiguration;

class ModuleConfiguration extends AbstractModuleConfiguration
{
    // public function getPostCategoryListDefaultLimit(): ?int
    // {
    //     // Define properties
    //     $envVariable = Environment::POSTCATEGORY_LIST_DEFAULT_LIMIT;
    //     $defaultValue = 10;
    //     $callback = EnvironmentValueHelpers::toInt(...);

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
    //     $callback = EnvironmentValueHelpers::toInt(...);

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
