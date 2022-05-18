<?php

define('GD_SIDEBARSECTION_LOCATIONPOST', 'locationpost');
define('GD_COMPACTSIDEBARSECTION_LOCATIONPOST', 'compact-locationpost');

class Custom_EM_FullViewSidebarSettings
{
    public static function getSidebarSubmodules($section)
    {
        $ret = array();

        switch ($section) {
            case GD_SIDEBARSECTION_LOCATIONPOST:
                $ret[] = [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_FEATUREDIMAGE];
                $ret[] = [PoP_Module_Processor_SocialMediaPostWrappers::class, PoP_Module_Processor_SocialMediaPostWrappers::COMPONENT_POSTSOCIALMEDIA_POSTWRAPPER];
                // Only if the Volunteering is enabled
                if (defined('POP_VOLUNTEERING_ROUTE_VOLUNTEER') && POP_VOLUNTEERING_ROUTE_VOLUNTEER) {
                    $ret[] = [PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::class, PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_BIG];
                }
                if (PoP_ApplicationProcessors_Utils::addCategoriesToWidget()) {
                    $ret[] = [PoP_Module_Processor_CustomPostWidgets::class, PoP_Module_Processor_CustomPostWidgets::COMPONENT_WIDGET_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [PoP_Module_Processor_CustomPostWidgets::class, PoP_Module_Processor_CustomPostWidgets::COMPONENT_WIDGET_APPLIESTO];
                }
                $ret[] = [PoP_Locations_Module_Processor_SidebarComponents::class, PoP_Locations_Module_Processor_SidebarComponents::COMPONENT_EM_WIDGET_POSTLOCATIONSMAP];
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::COMPONENT_WIDGET_POST_AUTHORS];
                break;

            case GD_COMPACTSIDEBARSECTION_LOCATIONPOST:
                // Only if the Volunteering is enabled
                if (defined('POP_VOLUNTEERING_ROUTE_VOLUNTEER') && POP_VOLUNTEERING_ROUTE_VOLUNTEER) {
                    $ret[] = [PoP_Module_Processor_CustomPostMultipleSidebarComponents::class, PoP_Module_Processor_CustomPostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_FEATUREDIMAGEVOLUNTEER];
                } else {
                    $ret[] = [PoP_Module_Processor_CustomPostMultipleSidebarComponents::class, PoP_Module_Processor_CustomPostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_FEATUREDIMAGE];
                }
                $ret[] = [GD_SP_Custom_EM_Module_Processor_PostMultipleSidebarComponents::class, GD_SP_Custom_EM_Module_Processor_PostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_LOCATIONPOST];
                break;
        }
        
        return $ret;
    }
}
