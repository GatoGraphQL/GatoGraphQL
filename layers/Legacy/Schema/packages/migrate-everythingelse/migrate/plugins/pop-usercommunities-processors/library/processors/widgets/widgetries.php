<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_Widgets extends PoP_Module_Processor_WidgetsBase
{
    public final const MODULE_URE_WIDGET_COMMUNITIES = 'ure-widget-communities';
    public final const MODULE_URE_WIDGETCOMPACT_COMMUNITIES = 'ure-widgetcompact-communities';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_URE_WIDGET_COMMUNITIES],
            [self::class, self::COMPONENT_URE_WIDGETCOMPACT_COMMUNITIES],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_URE_WIDGET_COMMUNITIES:
            case self::COMPONENT_URE_WIDGETCOMPACT_COMMUNITIES:
                $ret[] = [GD_URE_Module_Processor_UserCommunityLayouts::class, GD_URE_Module_Processor_UserCommunityLayouts::COMPONENT_URE_LAYOUT_COMMUNITIES];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $component, array &$props)
    {
        $titles = array(
            self::COMPONENT_URE_WIDGET_COMMUNITIES => TranslationAPIFacade::getInstance()->__('Member of', 'ure-popprocessors'),
            self::COMPONENT_URE_WIDGETCOMPACT_COMMUNITIES => TranslationAPIFacade::getInstance()->__('Member of', 'ure-popprocessors'),
        );

        return $titles[$component[1]] ?? null;
    }
    public function getFontawesome(array $component, array &$props)
    {
        $fontawesomes = array(
            self::COMPONENT_URE_WIDGET_COMMUNITIES => 'fa-users',
            self::COMPONENT_URE_WIDGETCOMPACT_COMMUNITIES => 'fa-users',

        );

        return $fontawesomes[$component[1]] ?? null;
    }
    public function getBodyClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_WIDGET_COMMUNITIES:
                return 'list-group';

            case self::COMPONENT_URE_WIDGETCOMPACT_COMMUNITIES:
                return 'list-group list-group-sm';
        }

        return parent::getBodyClass($component, $props);
    }
    public function getItemWrapper(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_WIDGET_COMMUNITIES:
            case self::COMPONENT_URE_WIDGETCOMPACT_COMMUNITIES:
                return 'list-group-item';
        }

        return parent::getItemWrapper($component, $props);
    }
    public function getWidgetClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_WIDGETCOMPACT_COMMUNITIES:
                return 'panel panel-default panel-sm';
        }

        return parent::getWidgetClass($component, $props);
    }
}



