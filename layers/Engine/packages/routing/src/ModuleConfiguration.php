<?php

declare(strict_types=1);

namespace PoP\Root;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;
use PoP\Root\ModuleConfiguration as RootModuleConfiguration;
use PoP\Root\Module as RootModule;

class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function enablePassingRoutingStateViaRequest(): bool
    {
        /** @var RootModuleConfiguration */
        $moduleConfiguration = App::getModule(RootModule::class)->getConfiguration();
        if (!$moduleConfiguration->enablePassingStateViaRequest()) {
            return false;
        }

        $envVariable = Environment::ENABLE_PASSING_ROUTING_STATE_VIA_REQUEST;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
