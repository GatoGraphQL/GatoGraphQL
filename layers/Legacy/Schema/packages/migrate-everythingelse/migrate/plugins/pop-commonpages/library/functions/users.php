<?php
use PoP\Hooks\Facades\HooksAPIFacade;

/**
 * User IDs
 */

function getWhoweareCoreUserIds()
{
    return HooksAPIFacade::getInstance()->applyFilters('getWhoweareCoreUserIds', array());
}
