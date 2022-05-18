<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_EM_Module_Processor_PostWidgets extends PoP_Module_Processor_WidgetsBase
{
    public final const MODULE_WIDGET_LOCATIONPOST_CATEGORIES = 'widget-locationpost-categories';
    public final const MODULE_WIDGETCOMPACT_LOCATIONPOSTINFO = 'widgetcompact-locationpost-info';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_WIDGET_LOCATIONPOST_CATEGORIES],

            [self::class, self::COMPONENT_WIDGETCOMPACT_LOCATIONPOSTINFO],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_WIDGET_LOCATIONPOST_CATEGORIES:
                $ret[] = [GD_Custom_EM_Module_Processor_WidgetWrappers::class, GD_Custom_EM_Module_Processor_WidgetWrappers::COMPONENT_LAYOUTWRAPPER_LOCATIONPOST_CATEGORIES];
                break;

            case self::COMPONENT_WIDGETCOMPACT_LOCATIONPOSTINFO:
                if (PoP_ApplicationProcessors_Utils::addCategories()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::COMPONENT_LAYOUTWRAPPER_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::COMPONENT_LAYOUTWRAPPER_APPLIESTO];
                }
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTSIDEBARLOCATIONS];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $component, array &$props)
    {
        $categories = TranslationAPIFacade::getInstance()->__('Categories', 'pop-locationposts-processors');
        $titles = array(
            self::COMPONENT_WIDGET_LOCATIONPOST_CATEGORIES => $categories,
            self::COMPONENT_WIDGETCOMPACT_LOCATIONPOSTINFO => PoP_LocationPosts_PostNameUtils::getNameUc(),
        );

        return $titles[$component[1]] ?? null;
    }
    public function getFontawesome(array $component, array &$props)
    {
        $categories = 'fa-info-circle';
        $fontawesomes = array(
            self::COMPONENT_WIDGET_LOCATIONPOST_CATEGORIES => $categories,
            self::COMPONENT_WIDGETCOMPACT_LOCATIONPOSTINFO => getRouteIcon(POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS, false),
        );

        return $fontawesomes[$component[1]] ?? null;
    }

    public function getBodyClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGETCOMPACT_LOCATIONPOSTINFO:
                return 'list-group list-group-sm';
        }

        return parent::getBodyClass($component, $props);
    }
    public function getItemWrapper(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGETCOMPACT_LOCATIONPOSTINFO:
                return 'pop-hide-empty list-group-item';
        }

        return parent::getItemWrapper($component, $props);
    }
    public function getWidgetClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGETCOMPACT_LOCATIONPOSTINFO:
                return 'panel panel-default panel-sm';
        }

        return parent::getWidgetClass($component, $props);
    }
}



