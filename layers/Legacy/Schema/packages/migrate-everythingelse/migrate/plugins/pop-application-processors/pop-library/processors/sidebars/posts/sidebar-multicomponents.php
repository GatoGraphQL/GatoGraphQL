<?php

class PoP_Module_Processor_CustomPostMultipleSidebarComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_SIDEBARMULTICOMPONENT_FEATUREDIMAGE = 'sidebarmulticomponent-featuredimage';
    public final const MODULE_SIDEBARMULTICOMPONENT_FEATUREDIMAGEVOLUNTEER = 'sidebarmulticomponent-featuredimagevolunteer';
    public final const MODULE_SIDEBARMULTICOMPONENT_GENERIC = 'sidebarmulticomponent-generic';
    public final const MODULE_SIDEBARMULTICOMPONENT_HIGHLIGHTLEFT = 'sidebarmulticomponent-highlightleft';
    public final const MODULE_SIDEBARMULTICOMPONENT_HIGHLIGHTRIGHT = 'sidebarmulticomponent-highlightright';
    public final const MODULE_SIDEBARMULTICOMPONENT_POST = 'sidebarmulticomponent-post';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SIDEBARMULTICOMPONENT_FEATUREDIMAGE],
            [self::class, self::MODULE_SIDEBARMULTICOMPONENT_FEATUREDIMAGEVOLUNTEER],
            [self::class, self::MODULE_SIDEBARMULTICOMPONENT_GENERIC],
            [self::class, self::MODULE_SIDEBARMULTICOMPONENT_HIGHLIGHTLEFT],
            [self::class, self::MODULE_SIDEBARMULTICOMPONENT_HIGHLIGHTRIGHT],
            [self::class, self::MODULE_SIDEBARMULTICOMPONENT_POST],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::MODULE_SIDEBARMULTICOMPONENT_FEATUREDIMAGE:
                // Allow TPP Debate to override, adding UserStance_Module_Processor_WidgetWrappers::MODULE_BUTTONGROUPWRAPPER_STANCECOUNT
                $layouts = array();
                $layouts[] = [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_FEATUREDIMAGE];
                $layouts[] = [PoP_Module_Processor_SocialMediaPostWrappers::class, PoP_Module_Processor_SocialMediaPostWrappers::MODULE_POSTSOCIALMEDIA_POSTWRAPPER];
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

            case self::MODULE_SIDEBARMULTICOMPONENT_FEATUREDIMAGEVOLUNTEER:
                // Allow TPP Debate to override, adding UserStance_Module_Processor_WidgetWrappers::MODULE_BUTTONGROUPWRAPPER_STANCECOUNT
                $layouts = array();
                $layouts[] = [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER];
                // Added through a hook
                $layouts[] = [PoP_Module_Processor_SocialMediaPostWrappers::class, PoP_Module_Processor_SocialMediaPostWrappers::MODULE_POSTSOCIALMEDIA_POSTWRAPPER];
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
                
            case self::MODULE_SIDEBARMULTICOMPONENT_GENERIC:
                $ret[] = [PoP_Module_Processor_CustomPostWidgets::class, PoP_Module_Processor_CustomPostWidgets::MODULE_WIDGETCOMPACT_GENERICINFO];
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::MODULE_WIDGETCOMPACT_POST_AUTHORS];
                break;
                
            case self::MODULE_SIDEBARMULTICOMPONENT_POST:
                $ret[] = [PoP_Module_Processor_CustomPostWidgets::class, PoP_Module_Processor_CustomPostWidgets::MODULE_WIDGETCOMPACT_POSTINFO];
                $ret[] = [PoP_Module_Processor_SidebarComponentWrappers::class, PoP_Module_Processor_SidebarComponentWrappers::MODULE_WIDGETWRAPPER_REFERENCES];
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::MODULE_WIDGETCOMPACT_POST_AUTHORS];
                break;
                
            case self::MODULE_SIDEBARMULTICOMPONENT_HIGHLIGHTLEFT:
                $ret[] = [PoP_Module_Processor_CustomPostWidgets::class, PoP_Module_Processor_CustomPostWidgets::MODULE_WIDGETCOMPACT_HIGHLIGHTINFO];
                $ret[] = [Wassup_Module_Processor_Widgets::class, Wassup_Module_Processor_Widgets::MODULE_WIDGET_HIGHLIGHTS];
                $ret[] = [PoP_Module_Processor_SocialMediaPostWrappers::class, PoP_Module_Processor_SocialMediaPostWrappers::MODULE_SUBJUGATEDPOSTSOCIALMEDIA_POSTWRAPPER];
                break;
                
            case self::MODULE_SIDEBARMULTICOMPONENT_HIGHLIGHTRIGHT:
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::MODULE_WIDGETCOMPACT_POST_AUTHORS];
                break;
        }

        return $ret;
    }
}



