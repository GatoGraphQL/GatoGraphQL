<?php

declare(strict_types=1);

namespace PoPSchema\Comments;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    private static ?int $getRootCommentListDefaultLimit = 10;
    private static ?int $getCustomPostCommentOrCommentResponseListDefaultLimit = -1;
    private static ?int $getCommentListMaxLimit = -1;
    private static bool $treatCommentStatusAsAdminData = true;

    public static function getRootCommentListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::ROOT_COMMENT_LIST_DEFAULT_LIMIT;
        $selfProperty = &self::$getRootCommentListDefaultLimit;
        $defaultValue = 10;
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public static function getCustomPostCommentOrCommentResponseListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::CUSTOMPOST_COMMENT_OR_COMMENT_RESPONSE_LIST_DEFAULT_LIMIT;
        $selfProperty = &self::$getCustomPostCommentOrCommentResponseListDefaultLimit;
        $defaultValue = -1; // Unlimited
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public static function getCommentListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::COMMENT_LIST_MAX_LIMIT;
        $selfProperty = &self::$getCommentListMaxLimit;
        $defaultValue = -1;
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public static function treatCommentStatusAsAdminData(): bool
    {
        // Define properties
        $envVariable = Environment::TREAT_COMMENT_STATUS_AS_ADMIN_DATA;
        $selfProperty = &self::$treatCommentStatusAsAdminData;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }
}
