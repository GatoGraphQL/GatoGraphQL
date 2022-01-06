<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function getCustomPostListDefaultLimit(): ?int
    {
        $envVariable = Environment::CUSTOMPOST_LIST_DEFAULT_LIMIT;
        $defaultValue = 10;
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

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
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

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
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

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
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
