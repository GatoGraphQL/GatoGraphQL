<?php

declare(strict_types=1);

namespace PoPSchema\CommentMeta;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    private static array $getCommentMetaEntries = [];
    private static string $getCommentMetaBehavior = Behaviors::ALLOWLIST;

    public static function getCommentMetaEntries(): array
    {
        // Define properties
        $envVariable = Environment::COMMENT_META_ENTRIES;
        $selfProperty = &self::$getCommentMetaEntries;
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

    public static function getCommentMetaBehavior(): string
    {
        // Define properties
        $envVariable = Environment::COMMENT_META_BEHAVIOR;
        $selfProperty = &self::$getCommentMetaBehavior;
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
