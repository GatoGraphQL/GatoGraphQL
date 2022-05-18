<?php

class UserStance_Module_Processor_CustomPostWidgets extends PoP_Module_Processor_WidgetsBase
{
    public final const MODULE_WIDGETCOMPACT_STANCEINFO = 'widgetcompact-stance-info';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WIDGETCOMPACT_STANCEINFO],
        );
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_WIDGETCOMPACT_STANCEINFO:
                $ret[] = [PoP_Module_Processor_PublishedLayouts::class, PoP_Module_Processor_PublishedLayouts::MODULE_LAYOUT_WIDGETPUBLISHED];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $componentVariation, array &$props)
    {
        $titles = array(
            self::MODULE_WIDGETCOMPACT_STANCEINFO => PoP_UserStance_PostNameUtils::getNameUc(),
        );

        return $titles[$componentVariation[1]] ?? null;
    }
    public function getFontawesome(array $componentVariation, array &$props)
    {
        $fontawesomes = array(
            self::MODULE_WIDGETCOMPACT_STANCEINFO => 'fa-commenting-o',
        );

        return $fontawesomes[$componentVariation[1]] ?? null;
    }

    public function getBodyClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGETCOMPACT_STANCEINFO:
                return 'list-group list-group-sm';
        }

        return parent::getBodyClass($componentVariation, $props);
    }
    public function getItemWrapper(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGETCOMPACT_STANCEINFO:
                return 'pop-hide-empty list-group-item';
        }

        return parent::getItemWrapper($componentVariation, $props);
    }
    public function getWidgetClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGETCOMPACT_STANCEINFO:
                return 'panel panel-default panel-sm';
        }

        return parent::getWidgetClass($componentVariation, $props);
    }
}



