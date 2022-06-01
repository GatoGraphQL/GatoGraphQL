<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_EM_Module_Processor_PostWidgets extends PoP_Module_Processor_WidgetsBase
{
    public final const COMPONENT_WIDGET_LOCATIONPOST_CATEGORIES = 'widget-locationpost-categories';
    public final const COMPONENT_WIDGETCOMPACT_LOCATIONPOSTINFO = 'widgetcompact-locationpost-info';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_WIDGET_LOCATIONPOST_CATEGORIES,

            self::COMPONENT_WIDGETCOMPACT_LOCATIONPOSTINFO,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
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

    public function getMenuTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $categories = TranslationAPIFacade::getInstance()->__('Categories', 'pop-locationposts-processors');
        $titles = array(
            self::COMPONENT_WIDGET_LOCATIONPOST_CATEGORIES => $categories,
            self::COMPONENT_WIDGETCOMPACT_LOCATIONPOSTINFO => PoP_LocationPosts_PostNameUtils::getNameUc(),
        );

        return $titles[$component->name] ?? null;
    }
    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $categories = 'fa-info-circle';
        $fontawesomes = array(
            self::COMPONENT_WIDGET_LOCATIONPOST_CATEGORIES => $categories,
            self::COMPONENT_WIDGETCOMPACT_LOCATIONPOSTINFO => getRouteIcon(POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS, false),
        );

        return $fontawesomes[$component->name] ?? null;
    }

    public function getBodyClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_WIDGETCOMPACT_LOCATIONPOSTINFO:
                return 'list-group list-group-sm';
        }

        return parent::getBodyClass($component, $props);
    }
    public function getItemWrapper(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_WIDGETCOMPACT_LOCATIONPOSTINFO:
                return 'pop-hide-empty list-group-item';
        }

        return parent::getItemWrapper($component, $props);
    }
    public function getWidgetClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_WIDGETCOMPACT_LOCATIONPOSTINFO:
                return 'panel panel-default panel-sm';
        }

        return parent::getWidgetClass($component, $props);
    }
}



