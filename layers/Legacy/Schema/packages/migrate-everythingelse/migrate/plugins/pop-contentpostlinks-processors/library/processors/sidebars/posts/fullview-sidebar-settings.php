<?php

define('GD_SIDEBARSECTION_POSTLINK', 'contentpostlink');
define('GD_COMPACTSIDEBARSECTION_POSTLINK', 'compact-contentpostlink');

class PoP_ContentPostLinks_FullViewSidebarSettings
{
    public static function getSidebarSubmodules($section)
    {
        $ret = array();

        switch ($section) {
            case GD_SIDEBARSECTION_POSTLINK:
                $ret[] = [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_FEATUREDIMAGE];
                $ret[] = [PoP_Module_Processor_SocialMediaPostWrappers::class, PoP_Module_Processor_SocialMediaPostWrappers::COMPONENT_POSTSOCIALMEDIA_POSTWRAPPER];
                if (PoP_ApplicationProcessors_Utils::addCategoriesToWidget()) {
                    $ret[] = [PoP_Module_Processor_CustomPostWidgets::class, PoP_Module_Processor_CustomPostWidgets::COMPONENT_WIDGET_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [PoP_Module_Processor_CustomPostWidgets::class, PoP_Module_Processor_CustomPostWidgets::COMPONENT_WIDGET_APPLIESTO];
                }
                $ret[] = [PoP_ContentPostLinks_Module_Processor_CustomPostWidgets::class, PoP_ContentPostLinks_Module_Processor_CustomPostWidgets::COMPONENT_WIDGET_LINK_ACCESS];
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::COMPONENT_WIDGET_POST_AUTHORS];
                break;

            case GD_COMPACTSIDEBARSECTION_POSTLINK:
                $ret[] = [PoP_Module_Processor_CustomPostMultipleSidebarComponents::class, PoP_Module_Processor_CustomPostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_FEATUREDIMAGE];
                $ret[] = [PoP_ContentPostLinks_Module_Processor_CustomPostMultipleSidebarComponents::class, PoP_ContentPostLinks_Module_Processor_CustomPostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_LINK];
                break;
        }
        
        return $ret;
    }
}
