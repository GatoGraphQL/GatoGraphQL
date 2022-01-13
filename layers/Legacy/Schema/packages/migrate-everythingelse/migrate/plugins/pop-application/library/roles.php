<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

function gdRoles()
{
    return HooksAPIFacade::getInstance()->applyFilters('gdRoles', array());
}

function getUserRoleCombinations()
{
    return HooksAPIFacade::getInstance()->applyFilters('getUserRoleCombinations', array());
}
