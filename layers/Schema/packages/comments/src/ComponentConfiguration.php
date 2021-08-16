<?php

declare(strict_types=1);

namespace PoPSchema\Comments;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;
use PoPSchema\Users\Component as UsersComponent;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    private static ?int $getRootCommentListDefaultLimit = 100;
    private static ?int $getCustomPostCommentOrCommentResponseListDefaultLimit = -1;
    private static ?int $getCommentListMaxLimit = 10;
    private static bool $mustUserBeLoggedInToAddComment = true;

    public static function getRootCommentListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::ROOT_COMMENT_LIST_DEFAULT_LIMIT;
        $selfProperty = &self::$getRootCommentListDefaultLimit;
        $defaultValue = 100;
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

    public static function mustUserBeLoggedInToAddComment(): bool
    {
        // The Users package must be active
        if (!class_exists(UsersComponent::class)) {
            return false;
        }

        // Define properties
        $envVariable = Environment::MUST_USER_BE_LOGGED_IN_TO_ADD_COMMENT;
        $selfProperty = &self::$mustUserBeLoggedInToAddComment;
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
