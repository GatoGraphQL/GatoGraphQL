<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_Module_Processor_MenuWidgets extends PoP_Module_Processor_WidgetsBase
{
    public const MODULE_WIDGET_MENU_ABOUT = 'widget-menu-about';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WIDGET_MENU_ABOUT],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_WIDGET_MENU_ABOUT:
                $ret[] = [PoP_Module_Processor_IndentMenuLayouts::class, PoP_Module_Processor_IndentMenuLayouts::MODULE_LAYOUT_MENU_INDENT];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $module, array &$props)
    {
        $menu = TranslationAPIFacade::getInstance()->__('Section links', 'poptheme-wassup');

        $titles = array(
            self::MODULE_WIDGET_MENU_ABOUT => $menu,
        );

        return $titles[$module[1]] ?? null;
    }
    public function getFontawesome(array $module, array &$props)
    {
        $menu = 'fa-sitemap';

        $fontawesomes = array(
            self::MODULE_WIDGET_MENU_ABOUT => $menu,
        );

        return $fontawesomes[$module[1]] ?? null;
    }

    public function getBodyClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_MENU_ABOUT:
                return 'panel-body';
        }

        return parent::getBodyClass($module, $props);
    }
    public function getItemWrapper(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_MENU_ABOUT:
                return '';
        }

        return parent::getItemWrapper($module, $props);
    }
}



