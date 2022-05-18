<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CustomPostWidgets extends PoP_Module_Processor_WidgetsBase
{
    public final const MODULE_WIDGET_CATEGORIES = 'widget-categories';
    public final const MODULE_WIDGET_APPLIESTO = 'widget-appliesto';
    public final const MODULE_WIDGETCOMPACT_GENERICINFO = 'widgetcompact-generic-info';
    public final const MODULE_WIDGETCOMPACT_HIGHLIGHTINFO = 'widgetcompact-highlight-info';
    public final const MODULE_WIDGETCOMPACT_POSTINFO = 'widgetcompact-post-info';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WIDGET_CATEGORIES],
            [self::class, self::MODULE_WIDGET_APPLIESTO],
            [self::class, self::MODULE_WIDGETCOMPACT_GENERICINFO],
            [self::class, self::MODULE_WIDGETCOMPACT_HIGHLIGHTINFO],
            [self::class, self::MODULE_WIDGETCOMPACT_POSTINFO],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_WIDGET_CATEGORIES:
                $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::MODULE_LAYOUTWRAPPER_CATEGORIES];
                break;

            case self::MODULE_WIDGET_APPLIESTO:
                $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::MODULE_LAYOUTWRAPPER_APPLIESTO];
                break;

            case self::MODULE_WIDGETCOMPACT_GENERICINFO:
            case self::MODULE_WIDGETCOMPACT_HIGHLIGHTINFO:
                $ret[] = [PoP_Module_Processor_PublishedLayouts::class, PoP_Module_Processor_PublishedLayouts::MODULE_LAYOUT_WIDGETPUBLISHED];
                break;

            case self::MODULE_WIDGETCOMPACT_POSTINFO:
                $ret[] = [PoP_Module_Processor_PublishedLayouts::class, PoP_Module_Processor_PublishedLayouts::MODULE_LAYOUT_WIDGETPUBLISHED];
                if (PoP_ApplicationProcessors_Utils::addCategories()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::MODULE_LAYOUTWRAPPER_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::MODULE_LAYOUTWRAPPER_APPLIESTO];
                }
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $module, array &$props)
    {
        $titles = array(
            self::MODULE_WIDGET_CATEGORIES => TranslationAPIFacade::getInstance()->__('Categories', 'poptheme-wassup'),
            self::MODULE_WIDGET_APPLIESTO => TranslationAPIFacade::getInstance()->__('Applies to', 'poptheme-wassup'),
            self::MODULE_WIDGETCOMPACT_GENERICINFO => TranslationAPIFacade::getInstance()->__('Post', 'poptheme-wassup'),
            self::MODULE_WIDGETCOMPACT_HIGHLIGHTINFO => TranslationAPIFacade::getInstance()->__('Highlight', 'poptheme-wassup'),
            self::MODULE_WIDGETCOMPACT_POSTINFO => TranslationAPIFacade::getInstance()->__('Post', 'poptheme-wassup'),
        );

        return $titles[$module[1]] ?? null;
    }
    public function getFontawesome(array $module, array &$props)
    {
        $fontawesomes = array(
            self::MODULE_WIDGET_CATEGORIES => 'fa-info-circle',
            self::MODULE_WIDGET_APPLIESTO => 'fa-info-circle',
            self::MODULE_WIDGETCOMPACT_GENERICINFO => 'fa-info-circle',
            self::MODULE_WIDGETCOMPACT_HIGHLIGHTINFO => 'fa-bullseye',
            // self::MODULE_WIDGETCOMPACT_POSTINFO => 'fa-flash',
            self::MODULE_WIDGETCOMPACT_POSTINFO => 'fa-circle',
        );

        return $fontawesomes[$module[1]] ?? null;
    }

    public function getBodyClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGETCOMPACT_GENERICINFO:
            case self::MODULE_WIDGETCOMPACT_HIGHLIGHTINFO:
            case self::MODULE_WIDGETCOMPACT_POSTINFO:
                return 'list-group list-group-sm';
        }

        return parent::getBodyClass($module, $props);
    }
    public function getItemWrapper(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGETCOMPACT_GENERICINFO:
            case self::MODULE_WIDGETCOMPACT_HIGHLIGHTINFO:
            case self::MODULE_WIDGETCOMPACT_POSTINFO:
                return 'pop-hide-empty list-group-item';
        }

        return parent::getItemWrapper($module, $props);
    }
    public function getWidgetClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGETCOMPACT_GENERICINFO:
            case self::MODULE_WIDGETCOMPACT_HIGHLIGHTINFO:
            case self::MODULE_WIDGETCOMPACT_POSTINFO:
                return 'panel panel-default panel-sm';
        }

        return parent::getWidgetClass($module, $props);
    }
}



