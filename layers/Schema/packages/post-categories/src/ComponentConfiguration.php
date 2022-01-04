<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    // private int $getPostCategoryListDefaultLimit = 10;
    // private int $getPostCategoryListMaxLimit = -1;
    private string $getPostCategoriesRoute = '';

    // public function getPostCategoryListDefaultLimit(): ?int
    // {
    //     // Define properties
    //     $envVariable = Environment::POSTCATEGORY_LIST_DEFAULT_LIMIT;
    //     $selfProperty = &$this->getPostCategoryListDefaultLimit;
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

    // public function getPostCategoryListMaxLimit(): ?int
    // {
    //     // Define properties
    //     $envVariable = Environment::POSTCATEGORY_LIST_MAX_LIMIT;
    //     $selfProperty = &$this->getPostCategoryListMaxLimit;
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

    public function getPostCategoriesRoute(): string
    {
        // Define properties
        $envVariable = Environment::POSTCATEGORIES_ROUTE;
        $selfProperty = &$this->getPostCategoriesRoute;
        $defaultValue = 'categories';

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue
        );
        return $selfProperty;
    }
}
