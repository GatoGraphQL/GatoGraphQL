<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Locations_Module_Processor_SidebarComponents extends PoP_Module_Processor_WidgetsBase
{
    public final const COMPONENT_EM_WIDGET_POSTLOCATIONSMAP = 'em-widget-postlocationsmap';
    public final const COMPONENT_EM_WIDGET_USERLOCATIONSMAP = 'em-widget-userlocationsmap';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_EM_WIDGET_POSTLOCATIONSMAP],
            [self::class, self::COMPONENT_EM_WIDGET_USERLOCATIONSMAP],
        );
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_EM_WIDGET_POSTLOCATIONSMAP:
                $ret[] = [GD_EM_Module_Processor_LocationMapConditionWrappers::class, GD_EM_Module_Processor_LocationMapConditionWrappers::COMPONENT_EM_LAYOUTWRAPPER_POSTLOCATIONSMAP];
                break;

            case self::COMPONENT_EM_WIDGET_USERLOCATIONSMAP:
                $ret[] = [GD_EM_Module_Processor_LocationMapConditionWrappers::class, GD_EM_Module_Processor_LocationMapConditionWrappers::COMPONENT_EM_LAYOUTWRAPPER_USERLOCATIONSMAP];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $titles = array(
            self::COMPONENT_EM_WIDGET_POSTLOCATIONSMAP => TranslationAPIFacade::getInstance()->__('Location(s)', 'poptheme-wassup'),
            self::COMPONENT_EM_WIDGET_USERLOCATIONSMAP => TranslationAPIFacade::getInstance()->__('Location(s)', 'poptheme-wassup'),
        );

        return $titles[$component[1]] ?? null;
    }
    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $fontawesomes = array(
            self::COMPONENT_EM_WIDGET_POSTLOCATIONSMAP => 'fa-map-marker',
            self::COMPONENT_EM_WIDGET_USERLOCATIONSMAP => 'fa-map-marker',
        );

        return $fontawesomes[$component[1]] ?? null;
    }
    public function getBodyClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_EM_WIDGET_POSTLOCATIONSMAP:
            case self::COMPONENT_EM_WIDGET_USERLOCATIONSMAP:
                return 'list-group';
        }

        return parent::getBodyClass($component, $props);
    }
    public function getItemWrapper(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_EM_WIDGET_POSTLOCATIONSMAP:
            case self::COMPONENT_EM_WIDGET_USERLOCATIONSMAP:
                return 'list-group-item';
        }

        return parent::getItemWrapper($component, $props);
    }
}


