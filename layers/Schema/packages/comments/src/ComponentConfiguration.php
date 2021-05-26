<?php

declare(strict_types=1);

namespace PoPSchema\Comments;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;
use PoPSchema\Users\Component as UsersComponent;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    private static bool $mustUserBeLoggedInToAddComment = true;

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
