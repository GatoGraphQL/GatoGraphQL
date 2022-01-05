<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;
use PoPSchema\Users\Component as UsersComponent;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private bool $mustUserBeLoggedInToAddComment = true;
    private bool $requireCommenterNameAndEmail = true;

    public function mustUserBeLoggedInToAddComment(): bool
    {
        // The Users package must be active
        if (!class_exists(UsersComponent::class)) {
            return false;
        }

        // Define properties
        $envVariable = Environment::MUST_USER_BE_LOGGED_IN_TO_ADD_COMMENT;
        $selfProperty = &$this->mustUserBeLoggedInToAddComment;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    public function requireCommenterNameAndEmail(): bool
    {
        // Define properties
        $envVariable = Environment::REQUIRE_COMMENTER_NAME_AND_EMAIL;
        $selfProperty = &$this->requireCommenterNameAndEmail;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }
}
