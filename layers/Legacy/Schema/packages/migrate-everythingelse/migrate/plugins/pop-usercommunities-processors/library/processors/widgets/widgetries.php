<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_Widgets extends PoP_Module_Processor_WidgetsBase
{
    public final const MODULE_URE_WIDGET_COMMUNITIES = 'ure-widget-communities';
    public final const MODULE_URE_WIDGETCOMPACT_COMMUNITIES = 'ure-widgetcompact-communities';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_WIDGET_COMMUNITIES],
            [self::class, self::MODULE_URE_WIDGETCOMPACT_COMMUNITIES],
        );
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_URE_WIDGET_COMMUNITIES:
            case self::MODULE_URE_WIDGETCOMPACT_COMMUNITIES:
                $ret[] = [GD_URE_Module_Processor_UserCommunityLayouts::class, GD_URE_Module_Processor_UserCommunityLayouts::MODULE_URE_LAYOUT_COMMUNITIES];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $componentVariation, array &$props)
    {
        $titles = array(
            self::MODULE_URE_WIDGET_COMMUNITIES => TranslationAPIFacade::getInstance()->__('Member of', 'ure-popprocessors'),
            self::MODULE_URE_WIDGETCOMPACT_COMMUNITIES => TranslationAPIFacade::getInstance()->__('Member of', 'ure-popprocessors'),
        );

        return $titles[$componentVariation[1]] ?? null;
    }
    public function getFontawesome(array $componentVariation, array &$props)
    {
        $fontawesomes = array(
            self::MODULE_URE_WIDGET_COMMUNITIES => 'fa-users',
            self::MODULE_URE_WIDGETCOMPACT_COMMUNITIES => 'fa-users',

        );

        return $fontawesomes[$componentVariation[1]] ?? null;
    }
    public function getBodyClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_WIDGET_COMMUNITIES:
                return 'list-group';

            case self::MODULE_URE_WIDGETCOMPACT_COMMUNITIES:
                return 'list-group list-group-sm';
        }

        return parent::getBodyClass($componentVariation, $props);
    }
    public function getItemWrapper(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_WIDGET_COMMUNITIES:
            case self::MODULE_URE_WIDGETCOMPACT_COMMUNITIES:
                return 'list-group-item';
        }

        return parent::getItemWrapper($componentVariation, $props);
    }
    public function getWidgetClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_WIDGETCOMPACT_COMMUNITIES:
                return 'panel panel-default panel-sm';
        }

        return parent::getWidgetClass($componentVariation, $props);
    }
}



