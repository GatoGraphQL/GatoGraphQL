<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Locations_Module_Processor_SidebarComponents extends PoP_Module_Processor_WidgetsBase
{
    public final const MODULE_EM_WIDGET_POSTLOCATIONSMAP = 'em-widget-postlocationsmap';
    public final const MODULE_EM_WIDGET_USERLOCATIONSMAP = 'em-widget-userlocationsmap';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EM_WIDGET_POSTLOCATIONSMAP],
            [self::class, self::MODULE_EM_WIDGET_USERLOCATIONSMAP],
        );
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_EM_WIDGET_POSTLOCATIONSMAP:
                $ret[] = [GD_EM_Module_Processor_LocationMapConditionWrappers::class, GD_EM_Module_Processor_LocationMapConditionWrappers::MODULE_EM_LAYOUTWRAPPER_POSTLOCATIONSMAP];
                break;

            case self::MODULE_EM_WIDGET_USERLOCATIONSMAP:
                $ret[] = [GD_EM_Module_Processor_LocationMapConditionWrappers::class, GD_EM_Module_Processor_LocationMapConditionWrappers::MODULE_EM_LAYOUTWRAPPER_USERLOCATIONSMAP];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $componentVariation, array &$props)
    {
        $titles = array(
            self::MODULE_EM_WIDGET_POSTLOCATIONSMAP => TranslationAPIFacade::getInstance()->__('Location(s)', 'poptheme-wassup'),
            self::MODULE_EM_WIDGET_USERLOCATIONSMAP => TranslationAPIFacade::getInstance()->__('Location(s)', 'poptheme-wassup'),
        );

        return $titles[$componentVariation[1]] ?? null;
    }
    public function getFontawesome(array $componentVariation, array &$props)
    {
        $fontawesomes = array(
            self::MODULE_EM_WIDGET_POSTLOCATIONSMAP => 'fa-map-marker',
            self::MODULE_EM_WIDGET_USERLOCATIONSMAP => 'fa-map-marker',
        );

        return $fontawesomes[$componentVariation[1]] ?? null;
    }
    public function getBodyClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_EM_WIDGET_POSTLOCATIONSMAP:
            case self::MODULE_EM_WIDGET_USERLOCATIONSMAP:
                return 'list-group';
        }

        return parent::getBodyClass($componentVariation, $props);
    }
    public function getItemWrapper(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_EM_WIDGET_POSTLOCATIONSMAP:
            case self::MODULE_EM_WIDGET_USERLOCATIONSMAP:
                return 'list-group-item';
        }

        return parent::getItemWrapper($componentVariation, $props);
    }
}


