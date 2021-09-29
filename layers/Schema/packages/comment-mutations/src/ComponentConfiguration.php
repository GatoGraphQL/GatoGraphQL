<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations;

use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;
use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoPSchema\Users\Component as UsersComponent;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    private static bool $mustUserBeLoggedInToAddComment = true;
    private static bool $requireCommenterNameAndEmail = true;

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

    public static function requireCommenterNameAndEmail(): bool
    {
        // Define properties
        $envVariable = Environment::REQUIRE_COMMENTER_NAME_AND_EMAIL;
        $selfProperty = &self::$requireCommenterNameAndEmail;
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
