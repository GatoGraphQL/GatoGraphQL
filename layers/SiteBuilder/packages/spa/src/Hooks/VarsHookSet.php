<?php

declare(strict_types=1);

namespace PoP\SPA\Hooks;

use PoP\Hooks\AbstractHookSet;

class VarsHookSet extends AbstractHookSet
{
    protected function init()
    {
        $this->hooksAPI->addAction(
            'ApplicationState:addVars',
            array($this, 'addVars'),
            10,
            1
        );
    }

    /**
     * @param array<array> $vars_in_array
     */
    public function addVars(array $vars_in_array): void
    {
        [&$vars] = $vars_in_array;
        $vars['fetching-site'] = is_null($vars['modulefilter']);
        $vars['loading-site'] = $vars['fetching-site'] && $vars['output'] == \PoP\ComponentModel\Constants\Outputs::HTML;

        // Settings format: the format set by the application when first visiting it, configurable by the user
        if ($vars['loading-site'] ?? null) {
            $vars['settingsformat'] = strtolower($_REQUEST[\PoP\ComponentModel\Constants\Params::FORMAT] ?? '');
        } else {
            $vars['settingsformat'] = strtolower($_REQUEST[\PoP\ComponentModel\Constants\Params::SETTINGSFORMAT] ?? '');
        }

        // Format: if not set, then use the 'settingsFormat' value if it has been set.
        if (!isset($_REQUEST[\PoP\ComponentModel\Constants\Params::FORMAT]) && isset($_REQUEST[\PoP\ComponentModel\Constants\Params::SETTINGSFORMAT])) {
            $vars['format'] = $vars['settingsformat'];
        }
    }
}
