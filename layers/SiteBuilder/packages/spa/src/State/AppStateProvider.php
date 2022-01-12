<?php

declare(strict_types=1);

namespace PoP\SPA\State;

use PoP\ComponentModel\Constants\Outputs;
use PoP\ConfigurationComponentModel\Constants\Params;
use PoP\Root\State\AbstractAppStateProvider;

class AppStateProvider extends AbstractAppStateProvider
{
    public function consolidate(array &$state): void
    {
        $state['fetching-site'] = is_null($state['modulefilter']);
        $state['loading-site'] = $state['fetching-site'] && $state['output'] === Outputs::HTML;

        // Settings format: the format set by the application when first visiting it, configurable by the user
        if ($state['loading-site'] ?? null) {
            $state['settingsformat'] = strtolower($_REQUEST[Params::FORMAT] ?? '');
        } else {
            $state['settingsformat'] = strtolower($_REQUEST[Params::SETTINGSFORMAT] ?? '');
        }

        // Format: if not set, then use the 'settingsFormat' value if it has been set.
        if (!isset($_REQUEST[Params::FORMAT]) && isset($_REQUEST[Params::SETTINGSFORMAT])) {
            $state['format'] = $state['settingsformat'];
        }
    }
}
