<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_ContentCreation_UserState_Utils
{
    public static function requireUserStateForContentcreationPages()
    {
        return HooksAPIFacade::getInstance()->applyFilters('requireUserStateForContentcreationPages', false);
    }
}
