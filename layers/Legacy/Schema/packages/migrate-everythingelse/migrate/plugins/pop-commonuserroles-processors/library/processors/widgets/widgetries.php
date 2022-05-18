<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Custom_Module_Processor_Widgets extends PoP_Module_Processor_WidgetsBase
{
    public final const COMPONENT_URE_WIDGET_PROFILEORGANIZATION_DETAILS = 'ure-widget-profileorganization-details';
    public final const COMPONENT_URE_WIDGET_PROFILEINDIVIDUAL_DETAILS = 'ure-widget-profileindividual-details';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_URE_WIDGET_PROFILEORGANIZATION_DETAILS],
            [self::class, self::COMPONENT_URE_WIDGET_PROFILEINDIVIDUAL_DETAILS],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_URE_WIDGET_PROFILEINDIVIDUAL_DETAILS:
                $ret[] = [GD_URE_Custom_Module_Processor_SidebarComponentsWrappers::class, GD_URE_Custom_Module_Processor_SidebarComponentsWrappers::COMPONENT_URE_LAYOUTWRAPPER_PROFILEINDIVIDUAL_DETAILS];
                break;

            case self::COMPONENT_URE_WIDGET_PROFILEORGANIZATION_DETAILS:
                $ret[] = [GD_URE_Custom_Module_Processor_SidebarComponentsWrappers::class, GD_URE_Custom_Module_Processor_SidebarComponentsWrappers::COMPONENT_URE_LAYOUTWRAPPER_PROFILEORGANIZATION_DETAILS];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $component, array &$props)
    {
        $titles = array(
            self::COMPONENT_URE_WIDGET_PROFILEORGANIZATION_DETAILS => TranslationAPIFacade::getInstance()->__('Details', 'poptheme-wassup'),
            self::COMPONENT_URE_WIDGET_PROFILEINDIVIDUAL_DETAILS => TranslationAPIFacade::getInstance()->__('Details', 'poptheme-wassup'),
        );

        return $titles[$component[1]] ?? null;
    }
    public function getFontawesome(array $component, array &$props)
    {
        $fontawesomes = array(
            self::COMPONENT_URE_WIDGET_PROFILEORGANIZATION_DETAILS => 'fa-info-circle',
            self::COMPONENT_URE_WIDGET_PROFILEINDIVIDUAL_DETAILS => 'fa-info-circle',

        );

        return $fontawesomes[$component[1]] ?? null;
    }
}



