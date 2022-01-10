<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\State;

use PoP\ConfigurationComponentModel\Configuration\Request;
use PoP\Root\State\AbstractAppStateProvider;

class AppStateProvider extends AbstractAppStateProvider
{
    /**
     * Have the Component set its own state, accessible for all Components in the App
     *
     * @param array<string,mixed> $state
     */
    public function initialize(array &$state): void
    {
        // Override the settings from ComponentModel
        $state['dataoutputitems'] = Request::getDataOutputItems();
    }
}
