<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomyMeta;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    /**
     * @return string[]
     */
    public function getTaxonomyMetaEntries(): array
    {
        // Define properties
        $envVariable = Environment::TAXONOMY_META_ENTRIES;
        $defaultValue = [];
        $callback = [EnvironmentValueHelpers::class, 'commaSeparatedStringToArray'];

        // Initialize property from the environment/hook
        $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    public function getTaxonomyMetaBehavior(): string
    {
        // Define properties
        $envVariable = Environment::TAXONOMY_META_BEHAVIOR;
        $defaultValue = Behaviors::ALLOWLIST;

        // Initialize property from the environment/hook
        $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
        return $this->configuration[$envVariable];
    }
}
