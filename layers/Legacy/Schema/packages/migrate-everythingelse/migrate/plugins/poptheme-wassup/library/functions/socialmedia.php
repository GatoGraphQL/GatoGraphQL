<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

function gdTwitterUser($add_at = true)
{
    if ($handle = HooksAPIFacade::getInstance()->applyFilters('gdTwitterUser', '')) {
        return $add_at ? '@'.$handle : $handle;
    }

    return '';
}
