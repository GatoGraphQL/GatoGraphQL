<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoPTheme_Wassup_MultiDomain_Utils
{
    public static function getMultidomainBgcolors()
    {
        return HooksAPIFacade::getInstance()->applyFilters('PoPTheme_Wassup_MultiDomain_Utils:multidomain_bgcolors', array());
    }
}
