<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories;

use PoP\Root\Module\AbstractComponentConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function getCategoryListDefaultLimit(): ?int
    {
        $envVariable = Environment::CATEGORY_LIST_DEFAULT_LIMIT;
        $defaultValue = 10;
        $callback = EnvironmentValueHelpers::toInt(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function getCategoryListMaxLimit(): ?int
    {
        $envVariable = Environment::CATEGORY_LIST_MAX_LIMIT;
        $defaultValue = -1; // Unlimited
        $callback = EnvironmentValueHelpers::toInt(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
