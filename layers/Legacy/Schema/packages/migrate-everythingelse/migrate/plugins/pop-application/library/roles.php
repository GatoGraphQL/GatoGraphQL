<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

function gdRoles()
{
    return \PoP\Root\App::getHookManager()->applyFilters('gdRoles', array());
}

function getUserRoleCombinations()
{
    return \PoP\Root\App::getHookManager()->applyFilters('getUserRoleCombinations', array());
}
