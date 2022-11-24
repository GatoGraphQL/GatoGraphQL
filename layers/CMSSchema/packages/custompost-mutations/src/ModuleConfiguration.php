<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;

class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function usePayloadableCustomPostMutations(): bool
    {
        $envVariable = Environment::USE_PAYLOADABLE_CUSTOMPOST_MUTATIONS;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
