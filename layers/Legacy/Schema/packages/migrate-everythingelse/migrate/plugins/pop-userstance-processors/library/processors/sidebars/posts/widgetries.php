<?php

class UserStance_Module_Processor_CustomPostWidgets extends PoP_Module_Processor_WidgetsBase
{
    public final const MODULE_WIDGETCOMPACT_STANCEINFO = 'widgetcompact-stance-info';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WIDGETCOMPACT_STANCEINFO],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_WIDGETCOMPACT_STANCEINFO:
                $ret[] = [PoP_Module_Processor_PublishedLayouts::class, PoP_Module_Processor_PublishedLayouts::MODULE_LAYOUT_WIDGETPUBLISHED];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $component, array &$props)
    {
        $titles = array(
            self::MODULE_WIDGETCOMPACT_STANCEINFO => PoP_UserStance_PostNameUtils::getNameUc(),
        );

        return $titles[$component[1]] ?? null;
    }
    public function getFontawesome(array $component, array &$props)
    {
        $fontawesomes = array(
            self::MODULE_WIDGETCOMPACT_STANCEINFO => 'fa-commenting-o',
        );

        return $fontawesomes[$component[1]] ?? null;
    }

    public function getBodyClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_WIDGETCOMPACT_STANCEINFO:
                return 'list-group list-group-sm';
        }

        return parent::getBodyClass($component, $props);
    }
    public function getItemWrapper(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_WIDGETCOMPACT_STANCEINFO:
                return 'pop-hide-empty list-group-item';
        }

        return parent::getItemWrapper($component, $props);
    }
    public function getWidgetClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_WIDGETCOMPACT_STANCEINFO:
                return 'panel panel-default panel-sm';
        }

        return parent::getWidgetClass($component, $props);
    }
}



