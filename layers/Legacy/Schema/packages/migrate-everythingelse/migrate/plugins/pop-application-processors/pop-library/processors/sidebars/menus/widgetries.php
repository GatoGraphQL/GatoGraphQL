<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_Module_Processor_MenuWidgets extends PoP_Module_Processor_WidgetsBase
{
    public final const MODULE_WIDGET_MENU_ABOUT = 'widget-menu-about';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WIDGET_MENU_ABOUT],
        );
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_WIDGET_MENU_ABOUT:
                $ret[] = [PoP_Module_Processor_IndentMenuLayouts::class, PoP_Module_Processor_IndentMenuLayouts::MODULE_LAYOUT_MENU_INDENT];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $componentVariation, array &$props)
    {
        $menu = TranslationAPIFacade::getInstance()->__('Section links', 'poptheme-wassup');

        $titles = array(
            self::MODULE_WIDGET_MENU_ABOUT => $menu,
        );

        return $titles[$componentVariation[1]] ?? null;
    }
    public function getFontawesome(array $componentVariation, array &$props)
    {
        $menu = 'fa-sitemap';

        $fontawesomes = array(
            self::MODULE_WIDGET_MENU_ABOUT => $menu,
        );

        return $fontawesomes[$componentVariation[1]] ?? null;
    }

    public function getBodyClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGET_MENU_ABOUT:
                return 'panel-body';
        }

        return parent::getBodyClass($componentVariation, $props);
    }
    public function getItemWrapper(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGET_MENU_ABOUT:
                return '';
        }

        return parent::getItemWrapper($componentVariation, $props);
    }
}



