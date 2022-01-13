<?php

class PoPTheme_Wassup_MultiDomain_Utils
{
    public static function getMultidomainBgcolors()
    {
        return \PoP\Root\App::applyFilters('PoPTheme_Wassup_MultiDomain_Utils:multidomain_bgcolors', array());
    }
}
