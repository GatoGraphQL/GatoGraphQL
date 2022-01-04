<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMeta;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private array $getCustomPostMetaEntries = [];
    private string $getCustomPostMetaBehavior = Behaviors::ALLOWLIST;

    public function getCustomPostMetaEntries(): array
    {
        // Define properties
        $envVariable = Environment::CUSTOMPOST_META_ENTRIES;
        $selfProperty = &$this->getCustomPostMetaEntries;
        $defaultValue = [];
        $callback = [EnvironmentValueHelpers::class, 'commaSeparatedStringToArray'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function getCustomPostMetaBehavior(): string
    {
        // Define properties
        $envVariable = Environment::CUSTOMPOST_META_BEHAVIOR;
        $selfProperty = &$this->getCustomPostMetaBehavior;
        $defaultValue = Behaviors::ALLOWLIST;

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue
        );
        return $selfProperty;
    }
}
