<?php

declare(strict_types=1);

namespace PoP\SPA\State;

use PoP\ComponentModel\Constants\Outputs;
use PoP\ConfigurationComponentModel\Constants\Params;
use PoP\ConfigurationComponentModel\Constants\Values;
use PoP\Root\App;
use PoP\Root\Component as RootComponent;
use PoP\Root\ComponentConfiguration as RootComponentConfiguration;
use PoP\Root\State\AbstractAppStateProvider;

class AppStateProvider extends AbstractAppStateProvider
{
    public function initialize(array &$state): void
    {
        /** @var RootComponentConfiguration */
        $rootComponentConfiguration = App::getComponent(RootComponent::class)->getConfiguration();
        if ($rootComponentConfiguration->enablePassingStateViaRequest()) {
            $state['settingsformat'] = App::request(Params::SETTINGSFORMAT) ?? App::query(Params::SETTINGSFORMAT, Values::DEFAULT);
        } else {
            $state['settingsformat'] = Values::DEFAULT;
        }
    }

    public function consolidate(array &$state): void
    {
        $state['fetching-site'] = $state['modulefilter'] === null;
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
