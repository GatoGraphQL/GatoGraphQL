<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/**
 * User IDs
 */

function getWhoweareCoreUserIds()
{
    return HooksAPIFacade::getInstance()->applyFilters('getWhoweareCoreUserIds', array());
}
