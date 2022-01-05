<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMeta;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    /**
     * @return string[]
     */
    public function getCustomPostMetaEntries(): array
    {
        // Define properties
        $envVariable = Environment::CUSTOMPOST_META_ENTRIES;
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

    public function getCustomPostMetaBehavior(): string
    {
        // Define properties
        $envVariable = Environment::CUSTOMPOST_META_BEHAVIOR;
        $defaultValue = Behaviors::ALLOWLIST;

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
        );
        return $this->configuration[$envVariable];
    }
}
