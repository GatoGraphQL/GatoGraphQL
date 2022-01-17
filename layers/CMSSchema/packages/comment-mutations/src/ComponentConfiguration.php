<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations;

use PoP\Root\Component\AbstractComponentConfiguration;
use PoP\Root\Component\EnvironmentValueHelpers;
use PoPCMSSchema\Users\Component as UsersComponent;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function mustUserBeLoggedInToAddComment(): bool
    {
        // The Users package must be active
        if (!class_exists(UsersComponent::class)) {
            return false;
        }

        $envVariable = Environment::MUST_USER_BE_LOGGED_IN_TO_ADD_COMMENT;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function requireCommenterNameAndEmail(): bool
    {
        $envVariable = Environment::REQUIRE_COMMENTER_NAME_AND_EMAIL;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
