<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_Widgets extends PoP_Module_Processor_WidgetsBase
{
    public final const COMPONENT_URE_WIDGET_COMMUNITIES = 'ure-widget-communities';
    public final const COMPONENT_URE_WIDGETCOMPACT_COMMUNITIES = 'ure-widgetcompact-communities';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_URE_WIDGET_COMMUNITIES,
            self::COMPONENT_URE_WIDGETCOMPACT_COMMUNITIES,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_URE_WIDGET_COMMUNITIES:
            case self::COMPONENT_URE_WIDGETCOMPACT_COMMUNITIES:
                $ret[] = [GD_URE_Module_Processor_UserCommunityLayouts::class, GD_URE_Module_Processor_UserCommunityLayouts::COMPONENT_URE_LAYOUT_COMMUNITIES];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $titles = array(
            self::COMPONENT_URE_WIDGET_COMMUNITIES => TranslationAPIFacade::getInstance()->__('Member of', 'ure-popprocessors'),
            self::COMPONENT_URE_WIDGETCOMPACT_COMMUNITIES => TranslationAPIFacade::getInstance()->__('Member of', 'ure-popprocessors'),
        );

        return $titles[$component->name] ?? null;
    }
    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $fontawesomes = array(
            self::COMPONENT_URE_WIDGET_COMMUNITIES => 'fa-users',
            self::COMPONENT_URE_WIDGETCOMPACT_COMMUNITIES => 'fa-users',

        );

        return $fontawesomes[$component->name] ?? null;
    }
    public function getBodyClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_URE_WIDGET_COMMUNITIES:
                return 'list-group';

            case self::COMPONENT_URE_WIDGETCOMPACT_COMMUNITIES:
                return 'list-group list-group-sm';
        }

        return parent::getBodyClass($component, $props);
    }
    public function getItemWrapper(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_URE_WIDGET_COMMUNITIES:
            case self::COMPONENT_URE_WIDGETCOMPACT_COMMUNITIES:
                return 'list-group-item';
        }

        return parent::getItemWrapper($component, $props);
    }
    public function getWidgetClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_URE_WIDGETCOMPACT_COMMUNITIES:
                return 'panel panel-default panel-sm';
        }

        return parent::getWidgetClass($component, $props);
    }
}



