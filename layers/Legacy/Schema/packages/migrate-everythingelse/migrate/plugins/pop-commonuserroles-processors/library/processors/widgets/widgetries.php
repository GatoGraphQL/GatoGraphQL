<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Custom_Module_Processor_Widgets extends PoP_Module_Processor_WidgetsBase
{
    public final const COMPONENT_URE_WIDGET_PROFILEORGANIZATION_DETAILS = 'ure-widget-profileorganization-details';
    public final const COMPONENT_URE_WIDGET_PROFILEINDIVIDUAL_DETAILS = 'ure-widget-profileindividual-details';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_URE_WIDGET_PROFILEORGANIZATION_DETAILS,
            self::COMPONENT_URE_WIDGET_PROFILEINDIVIDUAL_DETAILS,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_URE_WIDGET_PROFILEINDIVIDUAL_DETAILS:
                $ret[] = [GD_URE_Custom_Module_Processor_SidebarComponentsWrappers::class, GD_URE_Custom_Module_Processor_SidebarComponentsWrappers::COMPONENT_URE_LAYOUTWRAPPER_PROFILEINDIVIDUAL_DETAILS];
                break;

            case self::COMPONENT_URE_WIDGET_PROFILEORGANIZATION_DETAILS:
                $ret[] = [GD_URE_Custom_Module_Processor_SidebarComponentsWrappers::class, GD_URE_Custom_Module_Processor_SidebarComponentsWrappers::COMPONENT_URE_LAYOUTWRAPPER_PROFILEORGANIZATION_DETAILS];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $titles = array(
            self::COMPONENT_URE_WIDGET_PROFILEORGANIZATION_DETAILS => TranslationAPIFacade::getInstance()->__('Details', 'poptheme-wassup'),
            self::COMPONENT_URE_WIDGET_PROFILEINDIVIDUAL_DETAILS => TranslationAPIFacade::getInstance()->__('Details', 'poptheme-wassup'),
        );

        return $titles[$component->name] ?? null;
    }
    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $fontawesomes = array(
            self::COMPONENT_URE_WIDGET_PROFILEORGANIZATION_DETAILS => 'fa-info-circle',
            self::COMPONENT_URE_WIDGET_PROFILEINDIVIDUAL_DETAILS => 'fa-info-circle',

        );

        return $fontawesomes[$component->name] ?? null;
    }
}



