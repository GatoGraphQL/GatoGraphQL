<?php

define('GD_COMPACTSIDEBARSECTION_AUTOMATEDEMAILS_EVENT', 'compact-automatedemails-event');

class EM_AE_FullViewSidebarSettings
{
    public static function getSidebarSubcomponents($section)
    {
        $ret = array();

        switch ($section) {
            case GD_COMPACTSIDEBARSECTION_AUTOMATEDEMAILS_EVENT:
                // Only if the Volunteering is enabled
                if (defined('POP_VOLUNTEERING_ROUTE_VOLUNTEER') && POP_VOLUNTEERING_ROUTE_VOLUNTEER) {
                    $ret[] = [PoPTheme_Wassup_AE_Module_Processor_PostMultipleSidebarComponents::class, PoPTheme_Wassup_AE_Module_Processor_PostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_FEATUREDIMAGEVOLUNTEER];
                } else {
                    $ret[] = [PoPTheme_Wassup_AE_Module_Processor_PostMultipleSidebarComponents::class, PoPTheme_Wassup_AE_Module_Processor_PostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_FEATUREDIMAGE];
                }
                $ret[] = [PoPTheme_Wassup_EM_AE_Module_Processor_PostMultipleSidebarComponents::class, PoPTheme_Wassup_EM_AE_Module_Processor_PostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_EVENT];
                break;
        }
        
        return $ret;
    }
}
