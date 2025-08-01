<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;

class ModuleConfiguration extends AbstractModuleConfiguration
{
    /**
     * Indicate if to return the errors in an ObjectMutationPayload
     * object in the response, or if to use the top-level errors.
     */
    public function usePayloadableMediaMutations(): bool
    {
        $envVariable = Environment::USE_PAYLOADABLE_MEDIA_MUTATIONS;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function addFieldsToQueryPayloadableMediaMutations(): bool
    {
        if (!$this->usePayloadableMediaMutations()) {
            return false;
        }

        $envVariable = Environment::ADD_FIELDS_TO_QUERY_PAYLOADABLE_MEDIA_MUTATIONS;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function rejectUnsafeURLs(): bool
    {
        $envVariable = Environment::REJECT_UNSAFE_URLS;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
