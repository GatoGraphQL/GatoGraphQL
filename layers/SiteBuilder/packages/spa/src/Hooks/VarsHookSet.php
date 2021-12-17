<?php

declare(strict_types=1);

namespace PoP\SPA\Hooks;

use PoP\ComponentModel\Constants\Outputs;
use PoP\ComponentModel\Constants\Params;
use PoP\BasicService\AbstractHookSet;

class VarsHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addAction(
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
        $vars['loading-site'] = $vars['fetching-site'] && $vars['output'] == Outputs::HTML;

        // Settings format: the format set by the application when first visiting it, configurable by the user
        if ($vars['loading-site'] ?? null) {
            $vars['settingsformat'] = strtolower($_REQUEST[Params::FORMAT] ?? '');
        } else {
            $vars['settingsformat'] = strtolower($_REQUEST[Params::SETTINGSFORMAT] ?? '');
        }

        // Format: if not set, then use the 'settingsFormat' value if it has been set.
        if (!isset($_REQUEST[Params::FORMAT]) && isset($_REQUEST[Params::SETTINGSFORMAT])) {
            $vars['format'] = $vars['settingsformat'];
        }
    }
}
