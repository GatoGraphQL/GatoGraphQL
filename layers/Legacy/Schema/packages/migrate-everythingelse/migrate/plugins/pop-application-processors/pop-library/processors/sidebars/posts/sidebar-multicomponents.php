<?php

class PoP_Module_Processor_CustomPostMultipleSidebarComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_SIDEBARMULTICOMPONENT_FEATUREDIMAGE = 'sidebarmulticomponent-featuredimage';
    public final const COMPONENT_SIDEBARMULTICOMPONENT_FEATUREDIMAGEVOLUNTEER = 'sidebarmulticomponent-featuredimagevolunteer';
    public final const COMPONENT_SIDEBARMULTICOMPONENT_GENERIC = 'sidebarmulticomponent-generic';
    public final const COMPONENT_SIDEBARMULTICOMPONENT_HIGHLIGHTLEFT = 'sidebarmulticomponent-highlightleft';
    public final const COMPONENT_SIDEBARMULTICOMPONENT_HIGHLIGHTRIGHT = 'sidebarmulticomponent-highlightright';
    public final const COMPONENT_SIDEBARMULTICOMPONENT_POST = 'sidebarmulticomponent-post';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_SIDEBARMULTICOMPONENT_FEATUREDIMAGE,
            self::COMPONENT_SIDEBARMULTICOMPONENT_FEATUREDIMAGEVOLUNTEER,
            self::COMPONENT_SIDEBARMULTICOMPONENT_GENERIC,
            self::COMPONENT_SIDEBARMULTICOMPONENT_HIGHLIGHTLEFT,
            self::COMPONENT_SIDEBARMULTICOMPONENT_HIGHLIGHTRIGHT,
            self::COMPONENT_SIDEBARMULTICOMPONENT_POST,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_SIDEBARMULTICOMPONENT_FEATUREDIMAGE:
                // Allow TPP Debate to override, adding UserStance_Module_Processor_WidgetWrappers::COMPONENT_BUTTONGROUPWRAPPER_STANCECOUNT
                $layouts = array();
                $layouts[] = [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_FEATUREDIMAGE];
                $layouts[] = [PoP_Module_Processor_SocialMediaPostWrappers::class, PoP_Module_Processor_SocialMediaPostWrappers::COMPONENT_POSTSOCIALMEDIA_POSTWRAPPER];
                $layouts = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_CustomPostMultipleSidebarComponents:featuredimage:modules', 
                    $layouts, 
                    $component
                );
                $ret = array_merge(
                    $ret,
                    $layouts
                );
                break;

            case self::COMPONENT_SIDEBARMULTICOMPONENT_FEATUREDIMAGEVOLUNTEER:
                // Allow TPP Debate to override, adding UserStance_Module_Processor_WidgetWrappers::COMPONENT_BUTTONGROUPWRAPPER_STANCECOUNT
                $layouts = array();
                $layouts[] = [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER];
                // Added through a hook
                $layouts[] = [PoP_Module_Processor_SocialMediaPostWrappers::class, PoP_Module_Processor_SocialMediaPostWrappers::COMPONENT_POSTSOCIALMEDIA_POSTWRAPPER];
                $layouts = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_CustomPostMultipleSidebarComponents:featuredimagevolunteer:modules', 
                    $layouts, 
                    $component
                );
                $ret = array_merge(
                    $ret,
                    $layouts
                );
                break;
                
            case self::COMPONENT_SIDEBARMULTICOMPONENT_GENERIC:
                $ret[] = [PoP_Module_Processor_CustomPostWidgets::class, PoP_Module_Processor_CustomPostWidgets::COMPONENT_WIDGETCOMPACT_GENERICINFO];
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::COMPONENT_WIDGETCOMPACT_POST_AUTHORS];
                break;
                
            case self::COMPONENT_SIDEBARMULTICOMPONENT_POST:
                $ret[] = [PoP_Module_Processor_CustomPostWidgets::class, PoP_Module_Processor_CustomPostWidgets::COMPONENT_WIDGETCOMPACT_POSTINFO];
                $ret[] = [PoP_Module_Processor_SidebarComponentWrappers::class, PoP_Module_Processor_SidebarComponentWrappers::COMPONENT_WIDGETWRAPPER_REFERENCES];
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::COMPONENT_WIDGETCOMPACT_POST_AUTHORS];
                break;
                
            case self::COMPONENT_SIDEBARMULTICOMPONENT_HIGHLIGHTLEFT:
                $ret[] = [PoP_Module_Processor_CustomPostWidgets::class, PoP_Module_Processor_CustomPostWidgets::COMPONENT_WIDGETCOMPACT_HIGHLIGHTINFO];
                $ret[] = [Wassup_Module_Processor_Widgets::class, Wassup_Module_Processor_Widgets::COMPONENT_WIDGET_HIGHLIGHTS];
                $ret[] = [PoP_Module_Processor_SocialMediaPostWrappers::class, PoP_Module_Processor_SocialMediaPostWrappers::COMPONENT_SUBJUGATEDPOSTSOCIALMEDIA_POSTWRAPPER];
                break;
                
            case self::COMPONENT_SIDEBARMULTICOMPONENT_HIGHLIGHTRIGHT:
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::COMPONENT_WIDGETCOMPACT_POST_AUTHORS];
                break;
        }

        return $ret;
    }
}



