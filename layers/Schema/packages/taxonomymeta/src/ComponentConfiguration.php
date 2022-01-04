<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomyMeta;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private array $getTaxonomyMetaEntries = [];
    private string $getTaxonomyMetaBehavior = Behaviors::ALLOWLIST;

    public function getTaxonomyMetaEntries(): array
    {
        // Define properties
        $envVariable = Environment::TAXONOMY_META_ENTRIES;
        $selfProperty = &$this->getTaxonomyMetaEntries;
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

    public function getTaxonomyMetaBehavior(): string
    {
        // Define properties
        $envVariable = Environment::TAXONOMY_META_BEHAVIOR;
        $selfProperty = &$this->getTaxonomyMetaBehavior;
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
