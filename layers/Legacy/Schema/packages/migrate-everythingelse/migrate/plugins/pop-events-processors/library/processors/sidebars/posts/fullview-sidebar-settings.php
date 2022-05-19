<?php

define('GD_SIDEBARSECTION_EVENT', 'event');
define('GD_SIDEBARSECTION_PASTEVENT', 'pastevent');

define('GD_COMPACTSIDEBARSECTION_EVENT', 'compact-event');
define('GD_COMPACTSIDEBARSECTION_PASTEVENT', 'compact-pastevent');

class EM_FullViewSidebarSettings
{
    public static function getSidebarSubcomponents($section)
    {
        $ret = array();

        switch ($section) {
            case GD_SIDEBARSECTION_EVENT:
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
                $ret[] = [GD_EM_Module_Processor_SidebarComponents::class, GD_EM_Module_Processor_SidebarComponents::COMPONENT_EM_WIDGET_DATETIMEDOWNLOADLINKS];
                $ret[] = [PoP_Locations_Module_Processor_SidebarComponents::class, PoP_Locations_Module_Processor_SidebarComponents::COMPONENT_EM_WIDGET_POSTLOCATIONSMAP];
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::COMPONENT_WIDGET_POST_AUTHORS];
                break;

            case GD_SIDEBARSECTION_PASTEVENT:
                $ret[] = [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_FEATUREDIMAGE];
                $ret[] = [PoP_Module_Processor_SocialMediaPostWrappers::class, PoP_Module_Processor_SocialMediaPostWrappers::COMPONENT_POSTSOCIALMEDIA_POSTWRAPPER];
                if (PoP_ApplicationProcessors_Utils::addCategoriesToWidget()) {
                    $ret[] = [PoP_Module_Processor_CustomPostWidgets::class, PoP_Module_Processor_CustomPostWidgets::COMPONENT_WIDGET_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [PoP_Module_Processor_CustomPostWidgets::class, PoP_Module_Processor_CustomPostWidgets::COMPONENT_WIDGET_APPLIESTO];
                }
                $ret[] = [GD_EM_Module_Processor_SidebarComponents::class, GD_EM_Module_Processor_SidebarComponents::COMPONENT_EM_WIDGET_DATETIME];
                $ret[] = [PoP_Locations_Module_Processor_SidebarComponents::class, PoP_Locations_Module_Processor_SidebarComponents::COMPONENT_EM_WIDGET_POSTLOCATIONSMAP];
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::COMPONENT_WIDGET_POST_AUTHORS];
                break;


            case GD_COMPACTSIDEBARSECTION_EVENT:
                // Only if the Volunteering is enabled
                if (defined('POP_VOLUNTEERING_ROUTE_VOLUNTEER') && POP_VOLUNTEERING_ROUTE_VOLUNTEER) {
                    $ret[] = [PoP_Module_Processor_CustomPostMultipleSidebarComponents::class, PoP_Module_Processor_CustomPostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_FEATUREDIMAGEVOLUNTEER];
                } else {
                    $ret[] = [PoP_Module_Processor_CustomPostMultipleSidebarComponents::class, PoP_Module_Processor_CustomPostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_FEATUREDIMAGE];
                }
                $ret[] = [GD_EM_Module_Processor_PostMultipleSidebarComponents::class, GD_EM_Module_Processor_PostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_EVENT];
                break;

            case GD_COMPACTSIDEBARSECTION_PASTEVENT:
                $ret[] = [PoP_Module_Processor_CustomPostMultipleSidebarComponents::class, PoP_Module_Processor_CustomPostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_FEATUREDIMAGE];
                $ret[] = [GD_EM_Module_Processor_PostMultipleSidebarComponents::class, GD_EM_Module_Processor_PostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_PASTEVENT];
                break;
        }
        
        return $ret;
    }
}
