<?php

class PoP_ContentCreation_UserState_Utils
{
    public static function requireUserStateForContentcreationPages()
    {
        return \PoP\Root\App::applyFilters('requireUserStateForContentcreationPages', false);
    }
}
