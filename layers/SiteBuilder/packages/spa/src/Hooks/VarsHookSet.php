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
        $vars['loading-site'] = $vars['fetching-site'] && $vars['output'] == GD_URLPARAM_OUTPUT_HTML;

        // Settings format: the format set by the application when first visiting it, configurable by the user
        if ($vars['loading-site'] ?? null) {
            $vars['settingsformat'] = strtolower($_REQUEST[GD_URLPARAM_FORMAT] ?? '');
        } else {
            $vars['settingsformat'] = strtolower($_REQUEST[GD_URLPARAM_SETTINGSFORMAT] ?? '');
        }

        // Format: if not set, then use the 'settingsFormat' value if it has been set.
        if (!isset($_REQUEST[GD_URLPARAM_FORMAT]) && isset($_REQUEST[GD_URLPARAM_SETTINGSFORMAT])) {
            $vars['format'] = $vars['settingsformat'];
        }
    }
}
