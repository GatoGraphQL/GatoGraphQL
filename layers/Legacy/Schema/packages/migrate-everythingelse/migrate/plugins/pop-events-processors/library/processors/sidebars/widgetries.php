<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_EM_Module_Processor_SidebarComponents extends PoP_Module_Processor_WidgetsBase
{
    public final const MODULE_EM_WIDGET_DATETIMEDOWNLOADLINKS = 'em-widget-datetimedownloadlinks';
    public final const MODULE_EM_WIDGET_DATETIME = 'em-widget-datetime';
    public final const MODULE_EM_WIDGETCOMPACT_EVENTINFO = 'em-widgetcompact-eventinfo';
    public final const MODULE_EM_WIDGETCOMPACT_PASTEVENTINFO = 'em-widgetcompact-pasteventinfo';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EM_WIDGET_DATETIMEDOWNLOADLINKS],
            [self::class, self::MODULE_EM_WIDGET_DATETIME],
            [self::class, self::MODULE_EM_WIDGETCOMPACT_EVENTINFO],
            [self::class, self::MODULE_EM_WIDGETCOMPACT_PASTEVENTINFO],
        );
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_EM_WIDGET_DATETIMEDOWNLOADLINKS:
                $ret[] = [GD_EM_Module_Processor_DateTimeLayouts::class, GD_EM_Module_Processor_DateTimeLayouts::MODULE_EM_LAYOUT_DATETIMEDOWNLOADLINKS];
                break;

            case self::MODULE_EM_WIDGET_DATETIME:
                $ret[] = [GD_EM_Module_Processor_DateTimeLayouts::class, GD_EM_Module_Processor_DateTimeLayouts::MODULE_EM_LAYOUT_DATETIME];
                break;

            case self::MODULE_EM_WIDGETCOMPACT_EVENTINFO:
                if (PoP_ApplicationProcessors_Utils::addCategories()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::MODULE_LAYOUTWRAPPER_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::MODULE_LAYOUTWRAPPER_APPLIESTO];
                }
                $ret[] = [GD_EM_Module_Processor_DateTimeLayouts::class, GD_EM_Module_Processor_DateTimeLayouts::MODULE_EM_LAYOUT_DATETIMEDOWNLOADLINKS];
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTSIDEBARLOCATIONS];
                break;

            case self::MODULE_EM_WIDGETCOMPACT_PASTEVENTINFO:
                if (PoP_ApplicationProcessors_Utils::addCategories()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::MODULE_LAYOUTWRAPPER_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::MODULE_LAYOUTWRAPPER_APPLIESTO];
                }
                $ret[] = [GD_EM_Module_Processor_DateTimeLayouts::class, GD_EM_Module_Processor_DateTimeLayouts::MODULE_EM_LAYOUT_DATETIMEDOWNLOADLINKS];
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTSIDEBARLOCATIONS];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $componentVariation, array &$props)
    {
        $titles = array(
            self::MODULE_EM_WIDGET_DATETIMEDOWNLOADLINKS => TranslationAPIFacade::getInstance()->__('Date/Time', 'poptheme-wassup'),
            self::MODULE_EM_WIDGET_DATETIME => TranslationAPIFacade::getInstance()->__('Date/Time', 'poptheme-wassup'),
            self::MODULE_EM_WIDGETCOMPACT_EVENTINFO => TranslationAPIFacade::getInstance()->__('Event', 'poptheme-wassup'),
            self::MODULE_EM_WIDGETCOMPACT_PASTEVENTINFO => TranslationAPIFacade::getInstance()->__('Past Event', 'poptheme-wassup'),
        );

        return $titles[$componentVariation[1]] ?? null;
    }
    public function getFontawesome(array $componentVariation, array &$props)
    {
        $fontawesomes = array(
            self::MODULE_EM_WIDGET_DATETIMEDOWNLOADLINKS => 'fa-calendar',
            self::MODULE_EM_WIDGET_DATETIME => 'fa-calendar',
            self::MODULE_EM_WIDGETCOMPACT_EVENTINFO => 'fa-calendar',
            self::MODULE_EM_WIDGETCOMPACT_PASTEVENTINFO => 'fa-calendar',
        );

        return $fontawesomes[$componentVariation[1]] ?? null;
    }
    public function getBodyClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_EM_WIDGET_DATETIMEDOWNLOADLINKS:
            case self::MODULE_EM_WIDGET_DATETIME:
                return 'list-group';

            case self::MODULE_EM_WIDGETCOMPACT_EVENTINFO:
            case self::MODULE_EM_WIDGETCOMPACT_PASTEVENTINFO:
                return 'list-group list-group-sm';
        }

        return parent::getBodyClass($componentVariation, $props);
    }
    public function getItemWrapper(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_EM_WIDGET_DATETIMEDOWNLOADLINKS:
            case self::MODULE_EM_WIDGET_DATETIME:
                return 'list-group-item';

            case self::MODULE_EM_WIDGETCOMPACT_EVENTINFO:
            case self::MODULE_EM_WIDGETCOMPACT_PASTEVENTINFO:
                return 'pop-hide-empty list-group-item';
        }

        return parent::getItemWrapper($componentVariation, $props);
    }
    public function getWidgetClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_EM_WIDGETCOMPACT_EVENTINFO:
            case self::MODULE_EM_WIDGETCOMPACT_PASTEVENTINFO:
                // return 'panel panel-info panel-sm';
                return 'panel panel-default panel-sm';
        }

        return parent::getWidgetClass($componentVariation, $props);
    }
}


