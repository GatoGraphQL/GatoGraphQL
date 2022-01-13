<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_Module_Processor_UserAccountUtils
{
    public static function getLoginModules()
    {

        // Allow WSL to add the FB/Twitter Login
        return HooksAPIFacade::getInstance()->applyFilters(
            'PoP_Module_Processor_UserAccountUtils:login:modules',
            array()
        );
    }
}
