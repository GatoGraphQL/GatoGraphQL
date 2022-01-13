<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_EM_Module_Processor_PostWidgets extends PoP_Module_Processor_WidgetsBase
{
    public const MODULE_WIDGET_LOCATIONPOST_CATEGORIES = 'widget-locationpost-categories';
    public const MODULE_WIDGETCOMPACT_LOCATIONPOSTINFO = 'widgetcompact-locationpost-info';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WIDGET_LOCATIONPOST_CATEGORIES],

            [self::class, self::MODULE_WIDGETCOMPACT_LOCATIONPOSTINFO],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_WIDGET_LOCATIONPOST_CATEGORIES:
                $ret[] = [GD_Custom_EM_Module_Processor_WidgetWrappers::class, GD_Custom_EM_Module_Processor_WidgetWrappers::MODULE_LAYOUTWRAPPER_LOCATIONPOST_CATEGORIES];
                break;

            case self::MODULE_WIDGETCOMPACT_LOCATIONPOSTINFO:
                if (PoP_ApplicationProcessors_Utils::addCategories()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::MODULE_LAYOUTWRAPPER_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::MODULE_LAYOUTWRAPPER_APPLIESTO];
                }
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTSIDEBARLOCATIONS];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $module, array &$props)
    {
        $categories = TranslationAPIFacade::getInstance()->__('Categories', 'pop-locationposts-processors');
        $titles = array(
            self::MODULE_WIDGET_LOCATIONPOST_CATEGORIES => $categories,
            self::MODULE_WIDGETCOMPACT_LOCATIONPOSTINFO => PoP_LocationPosts_PostNameUtils::getNameUc(),
        );

        return $titles[$module[1]] ?? null;
    }
    public function getFontawesome(array $module, array &$props)
    {
        $categories = 'fa-info-circle';
        $fontawesomes = array(
            self::MODULE_WIDGET_LOCATIONPOST_CATEGORIES => $categories,
            self::MODULE_WIDGETCOMPACT_LOCATIONPOSTINFO => getRouteIcon(POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS, false),
        );

        return $fontawesomes[$module[1]] ?? null;
    }

    public function getBodyClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGETCOMPACT_LOCATIONPOSTINFO:
                return 'list-group list-group-sm';
        }

        return parent::getBodyClass($module, $props);
    }
    public function getItemWrapper(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGETCOMPACT_LOCATIONPOSTINFO:
                return 'pop-hide-empty list-group-item';
        }

        return parent::getItemWrapper($module, $props);
    }
    public function getWidgetClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGETCOMPACT_LOCATIONPOSTINFO:
                return 'panel panel-default panel-sm';
        }

        return parent::getWidgetClass($module, $props);
    }
}



