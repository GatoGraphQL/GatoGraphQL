<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoPTheme_Wassup_MultiDomain_Utils
{
    public static function getMultidomainBgcolors()
    {
        return \PoP\Root\App::getHookManager()->applyFilters('PoPTheme_Wassup_MultiDomain_Utils:multidomain_bgcolors', array());
    }
}
