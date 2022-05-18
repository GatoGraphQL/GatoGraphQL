<?php

const GD_SIDEBARSECTION_GENERIC = 'generic';
const GD_SIDEBARSECTION_POSTLINK = 'postlink';
const GD_SIDEBARSECTION_HIGHLIGHT = 'highlight';
const GD_SIDEBARSECTION_POST = 'post';
const GD_COMPACTSIDEBARSECTION_GENERIC = 'compact-generic';
const GD_COMPACTSIDEBARSECTION_POSTLINK = 'compact-postlink';
const GD_COMPACTSIDEBARSECTION_HIGHLIGHT = 'compact-highlight';
const GD_COMPACTSIDEBARSECTION_POST = 'compact-post';

class FullViewSidebarSettings
{
    public static function getSidebarSubmodules($section)
    {
        $ret = array();

        switch ($section) {
            case GD_SIDEBARSECTION_GENERIC:
                $ret[] = [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_FEATUREDIMAGE];
                $ret[] = [PoP_Module_Processor_SocialMediaPostWrappers::class, PoP_Module_Processor_SocialMediaPostWrappers::COMPONENT_POSTSOCIALMEDIA_POSTWRAPPER];
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::COMPONENT_WIDGET_POST_AUTHORS];
                break;

            case GD_SIDEBARSECTION_HIGHLIGHT:
                $ret[] = [PoP_Module_Processor_SocialMediaPostWrappers::class, PoP_Module_Processor_SocialMediaPostWrappers::COMPONENT_SUBJUGATEDPOSTSOCIALMEDIA_POSTWRAPPER];
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::COMPONENT_WIDGET_POST_AUTHORS];
                break;

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

            case GD_SIDEBARSECTION_POST:
                $ret[] = [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_FEATUREDIMAGE];
                $ret[] = [PoP_Module_Processor_SocialMediaPostWrappers::class, PoP_Module_Processor_SocialMediaPostWrappers::COMPONENT_POSTSOCIALMEDIA_POSTWRAPPER];
                if (PoP_ApplicationProcessors_Utils::addCategoriesToWidget()) {
                    $ret[] = [PoP_Module_Processor_CustomPostWidgets::class, PoP_Module_Processor_CustomPostWidgets::COMPONENT_WIDGET_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [PoP_Module_Processor_CustomPostWidgets::class, PoP_Module_Processor_CustomPostWidgets::COMPONENT_WIDGET_APPLIESTO];
                }
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::COMPONENT_WIDGET_POST_AUTHORS];
                break;

            case GD_COMPACTSIDEBARSECTION_GENERIC:
                $ret[] = [PoP_Module_Processor_CustomPostMultipleSidebarComponents::class, PoP_Module_Processor_CustomPostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_FEATUREDIMAGE];
                $ret[] = [PoP_Module_Processor_CustomPostMultipleSidebarComponents::class, PoP_Module_Processor_CustomPostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_GENERIC];
                break;

            case GD_COMPACTSIDEBARSECTION_POSTLINK:
                $ret[] = [PoP_Module_Processor_CustomPostMultipleSidebarComponents::class, PoP_Module_Processor_CustomPostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_FEATUREDIMAGE];
                $ret[] = [PoP_ContentPostLinks_Module_Processor_CustomPostMultipleSidebarComponents::class, PoP_ContentPostLinks_Module_Processor_CustomPostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_LINK];
                break;

            case GD_COMPACTSIDEBARSECTION_POST:
                // $ret[] = [PoP_Module_Processor_CustomPostMultipleSidebarComponents::class, PoP_Module_Processor_CustomPostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_POSTLEFT];
                // $ret[] = [PoP_Module_Processor_CustomPostMultipleSidebarComponents::class, PoP_Module_Processor_CustomPostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_POSTRIGHT];
                // Only if the Volunteering is enabled
                if (defined('POP_VOLUNTEERING_ROUTE_VOLUNTEER') && POP_VOLUNTEERING_ROUTE_VOLUNTEER) {
                    $ret[] = [PoP_Module_Processor_CustomPostMultipleSidebarComponents::class, PoP_Module_Processor_CustomPostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_FEATUREDIMAGEVOLUNTEER];
                } else {
                    $ret[] = [PoP_Module_Processor_CustomPostMultipleSidebarComponents::class, PoP_Module_Processor_CustomPostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_FEATUREDIMAGE];
                }
                $ret[] = [PoP_Module_Processor_CustomPostMultipleSidebarComponents::class, PoP_Module_Processor_CustomPostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_POST];
                break;

            case GD_COMPACTSIDEBARSECTION_HIGHLIGHT:
                $ret[] = [PoP_Module_Processor_CustomPostMultipleSidebarComponents::class, PoP_Module_Processor_CustomPostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_HIGHLIGHTLEFT];
                $ret[] = [PoP_Module_Processor_CustomPostMultipleSidebarComponents::class, PoP_Module_Processor_CustomPostMultipleSidebarComponents::COMPONENT_SIDEBARMULTICOMPONENT_HIGHLIGHTRIGHT];
                break;
        }
        
        return $ret;
    }
}
