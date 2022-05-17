<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractModuleConfiguration
{
    public function getCustomPostListDefaultLimit(): ?int
    {
        $envVariable = Environment::CUSTOMPOST_LIST_DEFAULT_LIMIT;
        $defaultValue = 10;
        $callback = EnvironmentValueHelpers::toInt(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function getCustomPostListMaxLimit(): ?int
    {
        $envVariable = Environment::CUSTOMPOST_LIST_MAX_LIMIT;
        $defaultValue = -1; // Unlimited
        $callback = EnvironmentValueHelpers::toInt(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function useSingleTypeInsteadOfCustomPostUnionType(): bool
    {
        $envVariable = Environment::USE_SINGLE_TYPE_INSTEAD_OF_CUSTOMPOST_UNION_TYPE;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function treatCustomPostStatusAsAdminData(): bool
    {
        $envVariable = Environment::TREAT_CUSTOMPOST_STATUS_AS_ADMIN_DATA;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
