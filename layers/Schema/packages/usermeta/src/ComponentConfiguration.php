<?php

declare(strict_types=1);

namespace PoPSchema\UserMeta;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private array $getUserMetaEntries = [];
    private string $getUserMetaBehavior = Behaviors::ALLOWLIST;

    public function getUserMetaEntries(): array
    {
        // Define properties
        $envVariable = Environment::USER_META_ENTRIES;
        $selfProperty = &$this->getUserMetaEntries;
        $defaultValue = [];
        $callback = [EnvironmentValueHelpers::class, 'commaSeparatedStringToArray'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    public function getUserMetaBehavior(): string
    {
        // Define properties
        $envVariable = Environment::USER_META_BEHAVIOR;
        $selfProperty = &$this->getUserMetaBehavior;
        $defaultValue = Behaviors::ALLOWLIST;

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
        );
        return $this->configuration[$envVariable];
    }
}
