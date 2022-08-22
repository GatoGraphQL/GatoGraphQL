<?php

declare(strict_types=1);

namespace PoP\SPA\State;

use PoP\ComponentModel\Constants\Outputs;
use PoP\ConfigurationComponentModel\Constants\Params;
use PoP\ConfigurationComponentModel\Constants\Values;
use PoP\Root\App;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootModuleConfiguration;
use PoP\Root\State\AbstractAppStateProvider;

class AppStateProvider extends AbstractAppStateProvider
{
    /**
     * @param array<string,mixed> $state
     */
    public function initialize(array &$state): void
    {
        /** @var RootModuleConfiguration */
        $rootModuleConfiguration = App::getModule(RootModule::class)->getConfiguration();
        if ($rootModuleConfiguration->enablePassingStateViaRequest()) {
            $state['settingsformat'] = App::request(Params::SETTINGSFORMAT) ?? App::query(Params::SETTINGSFORMAT, Values::DEFAULT);
        } else {
            $state['settingsformat'] = Values::DEFAULT;
        }
    }

    /**
     * @param array<string,mixed> $state
     */
    public function consolidate(array &$state): void
    {
        $state['fetching-site'] = $state['componentFilter'] === null;
        $state['loading-site'] = $state['fetching-site'] && $state['output'] === Outputs::HTML;

        // Settings format: the format set by the application when first visiting it, configurable by the user
        if ($state['loading-site']) {
            $state['settingsformat'] = $state['format'];
        }

        // Format: if not set, then use the 'settingsFormat' value if it has been set.
        if ($state['format'] === Values::DEFAULT) {
            $state['format'] = $state['settingsformat'];
        }
    }
}
