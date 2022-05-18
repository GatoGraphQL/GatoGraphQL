<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Custom_Module_Processor_Widgets extends PoP_Module_Processor_WidgetsBase
{
    public final const MODULE_URE_WIDGET_PROFILEORGANIZATION_DETAILS = 'ure-widget-profileorganization-details';
    public final const MODULE_URE_WIDGET_PROFILEINDIVIDUAL_DETAILS = 'ure-widget-profileindividual-details';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_WIDGET_PROFILEORGANIZATION_DETAILS],
            [self::class, self::MODULE_URE_WIDGET_PROFILEINDIVIDUAL_DETAILS],
        );
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_URE_WIDGET_PROFILEINDIVIDUAL_DETAILS:
                $ret[] = [GD_URE_Custom_Module_Processor_SidebarComponentsWrappers::class, GD_URE_Custom_Module_Processor_SidebarComponentsWrappers::MODULE_URE_LAYOUTWRAPPER_PROFILEINDIVIDUAL_DETAILS];
                break;

            case self::MODULE_URE_WIDGET_PROFILEORGANIZATION_DETAILS:
                $ret[] = [GD_URE_Custom_Module_Processor_SidebarComponentsWrappers::class, GD_URE_Custom_Module_Processor_SidebarComponentsWrappers::MODULE_URE_LAYOUTWRAPPER_PROFILEORGANIZATION_DETAILS];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $componentVariation, array &$props)
    {
        $titles = array(
            self::MODULE_URE_WIDGET_PROFILEORGANIZATION_DETAILS => TranslationAPIFacade::getInstance()->__('Details', 'poptheme-wassup'),
            self::MODULE_URE_WIDGET_PROFILEINDIVIDUAL_DETAILS => TranslationAPIFacade::getInstance()->__('Details', 'poptheme-wassup'),
        );

        return $titles[$componentVariation[1]] ?? null;
    }
    public function getFontawesome(array $componentVariation, array &$props)
    {
        $fontawesomes = array(
            self::MODULE_URE_WIDGET_PROFILEORGANIZATION_DETAILS => 'fa-info-circle',
            self::MODULE_URE_WIDGET_PROFILEINDIVIDUAL_DETAILS => 'fa-info-circle',

        );

        return $fontawesomes[$componentVariation[1]] ?? null;
    }
}



