<?php

define('GD_COMPACTSIDEBARSECTION_AUTOMATEDEMAILS_POST', 'compact-automatedemails-post');

class AE_FullViewSidebarSettings
{
    public static function getSidebarSubmodules($section)
    {
        $ret = array();

        switch ($section) {
            case GD_COMPACTSIDEBARSECTION_AUTOMATEDEMAILS_POST:
                $ret[] = [PoPTheme_Wassup_AE_Module_Processor_PostMultipleSidebarComponents::class, PoPTheme_Wassup_AE_Module_Processor_PostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_FEATUREDIMAGE];
                $ret[] = [PoPTheme_Wassup_AE_Module_Processor_PostMultipleSidebarComponents::class, PoPTheme_Wassup_AE_Module_Processor_PostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_POST];
                break;
        }
        
        return $ret;
    }
}
