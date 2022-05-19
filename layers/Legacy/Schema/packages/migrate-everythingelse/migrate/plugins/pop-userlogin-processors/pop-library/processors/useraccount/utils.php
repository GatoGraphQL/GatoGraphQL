<?php

class PoP_Module_Processor_UserAccountUtils
{
    public static function getLoginComponents()
    {

        // Allow WSL to add the FB/Twitter Login
        return \PoP\Root\App::applyFilters(
            'PoP_Module_Processor_UserAccountUtils:login:modules',
            array()
        );
    }
}
