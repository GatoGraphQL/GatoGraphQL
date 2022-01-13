<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

function gdTwitterUser($add_at = true)
{
    if ($handle = \PoP\Root\App::getHookManager()->applyFilters('gdTwitterUser', '')) {
        return $add_at ? '@'.$handle : $handle;
    }

    return '';
}
