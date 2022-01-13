<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/**
 * User IDs
 */

function getWhoweareCoreUserIds()
{
    return \PoP\Root\App::getHookManager()->applyFilters('getWhoweareCoreUserIds', array());
}
