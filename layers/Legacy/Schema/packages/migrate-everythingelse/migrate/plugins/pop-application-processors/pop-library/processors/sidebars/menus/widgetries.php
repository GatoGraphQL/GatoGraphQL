<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_Module_Processor_MenuWidgets extends PoP_Module_Processor_WidgetsBase
{
    public final const COMPONENT_WIDGET_MENU_ABOUT = 'widget-menu-about';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_WIDGET_MENU_ABOUT],
        );
    }

    public function getLayoutSubcomponents(array $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_WIDGET_MENU_ABOUT:
                $ret[] = [PoP_Module_Processor_IndentMenuLayouts::class, PoP_Module_Processor_IndentMenuLayouts::COMPONENT_LAYOUT_MENU_INDENT];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $component, array &$props)
    {
        $menu = TranslationAPIFacade::getInstance()->__('Section links', 'poptheme-wassup');

        $titles = array(
            self::COMPONENT_WIDGET_MENU_ABOUT => $menu,
        );

        return $titles[$component[1]] ?? null;
    }
    public function getFontawesome(array $component, array &$props)
    {
        $menu = 'fa-sitemap';

        $fontawesomes = array(
            self::COMPONENT_WIDGET_MENU_ABOUT => $menu,
        );

        return $fontawesomes[$component[1]] ?? null;
    }

    public function getBodyClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGET_MENU_ABOUT:
                return 'panel-body';
        }

        return parent::getBodyClass($component, $props);
    }
    public function getItemWrapper(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGET_MENU_ABOUT:
                return '';
        }

        return parent::getItemWrapper($component, $props);
    }
}



