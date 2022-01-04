<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private ?int $getCustomPostListDefaultLimit = 10;
    private ?int $getCustomPostListMaxLimit = -1;
    private bool $useSingleTypeInsteadOfCustomPostUnionType = false;
    private bool $treatCustomPostStatusAsAdminData = true;

    public function getCustomPostListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::CUSTOMPOST_LIST_DEFAULT_LIMIT;
        $selfProperty = &$this->getCustomPostListDefaultLimit;
        $defaultValue = 10;
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function getCustomPostListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::CUSTOMPOST_LIST_MAX_LIMIT;
        $selfProperty = &$this->getCustomPostListMaxLimit;
        $defaultValue = -1; // Unlimited
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function useSingleTypeInsteadOfCustomPostUnionType(): bool
    {
        // Define properties
        $envVariable = Environment::USE_SINGLE_TYPE_INSTEAD_OF_CUSTOMPOST_UNION_TYPE;
        $selfProperty = &$this->useSingleTypeInsteadOfCustomPostUnionType;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function treatCustomPostStatusAsAdminData(): bool
    {
        // Define properties
        $envVariable = Environment::TREAT_CUSTOMPOST_STATUS_AS_ADMIN_DATA;
        $selfProperty = &$this->treatCustomPostStatusAsAdminData;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }
}
