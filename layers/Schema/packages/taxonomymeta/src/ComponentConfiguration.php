<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomyMeta;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    private static array $getTaxonomyMetaEntries = [];
    private static string $getTaxonomyMetaBehavior = Behaviors::ALLOWLIST;

    public static function getTaxonomyMetaEntries(): array
    {
        // Define properties
        $envVariable = Environment::TAXONOMY_META_ENTRIES;
        $selfProperty = &self::$getTaxonomyMetaEntries;
        $defaultValue = [];
        $callback = [EnvironmentValueHelpers::class, 'commaSeparatedStringToArray'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public static function getTaxonomyMetaBehavior(): string
    {
        // Define properties
        $envVariable = Environment::TAXONOMY_META_BEHAVIOR;
        $selfProperty = &self::$getTaxonomyMetaBehavior;
        $defaultValue = Behaviors::ALLOWLIST;

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue
        );
        return $selfProperty;
    }
}
