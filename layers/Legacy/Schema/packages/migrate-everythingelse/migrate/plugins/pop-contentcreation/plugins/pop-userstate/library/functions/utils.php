<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_ContentCreation_UserState_Utils
{
    public static function requireUserStateForContentcreationPages()
    {
        return \PoP\Root\App::getHookManager()->applyFilters('requireUserStateForContentcreationPages', false);
    }
}
