<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;

class ModuleConfiguration extends AbstractModuleConfiguration
{
    /**
     * Indicate if to return the errors in an ObjectMutationPayload
     * object in the response, or if to use the top-level errors.
     */
    public function enableAdvancedMode(): bool
    {
        $envVariable = Environment::ENABLE_ADVANCED_MODE;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function disableAutomaticConfigUpdates(): bool
    {
        if (!$this->enableAdvancedMode()) {
            return false;
        }

        $envVariable = Environment::DISABLE_AUTOMATIC_CONFIG_UPDATES;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function overrideErrorFeedbackItemProviderObjectNotFoundErrorMessage(): bool
    {
        $envVariable = Environment::OVERRIDE_ERROR_FEEDBACK_ITEM_PROVIDER_OBJECT_NOT_FOUND_ERROR_MESSAGE;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
