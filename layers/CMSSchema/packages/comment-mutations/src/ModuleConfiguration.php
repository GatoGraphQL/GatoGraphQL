<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;
use PoPCMSSchema\Users\Module as UsersModule;

class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function mustUserBeLoggedInToAddComment(): bool
    {
        // The Users package must be active
        if (!class_exists(UsersModule::class)) {
            return false;
        }

        $envVariable = Environment::MUST_USER_BE_LOGGED_IN_TO_ADD_COMMENT;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

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
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
    /**
     * Indicate if to return the errors in an ObjectMutationPayload
     * object in the response, or if to use the top-level errors.
     */
    public function usePayloadableCommentMutations(): bool
    {
        $envVariable = Environment::USE_PAYLOADABLE_COMMENT_MUTATIONS;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function addFieldsToQueryPayloadableCommentMutations(): bool
    {
        $envVariable = Environment::ADD_FIELDS_TO_QUERY_PAYLOADABLE_COMMENT_MUTATIONS;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
