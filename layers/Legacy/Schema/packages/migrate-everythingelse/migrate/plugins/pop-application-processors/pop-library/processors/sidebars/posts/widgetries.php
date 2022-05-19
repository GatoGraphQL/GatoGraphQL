<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CustomPostWidgets extends PoP_Module_Processor_WidgetsBase
{
    public final const COMPONENT_WIDGET_CATEGORIES = 'widget-categories';
    public final const COMPONENT_WIDGET_APPLIESTO = 'widget-appliesto';
    public final const COMPONENT_WIDGETCOMPACT_GENERICINFO = 'widgetcompact-generic-info';
    public final const COMPONENT_WIDGETCOMPACT_HIGHLIGHTINFO = 'widgetcompact-highlight-info';
    public final const COMPONENT_WIDGETCOMPACT_POSTINFO = 'widgetcompact-post-info';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_WIDGET_CATEGORIES],
            [self::class, self::COMPONENT_WIDGET_APPLIESTO],
            [self::class, self::COMPONENT_WIDGETCOMPACT_GENERICINFO],
            [self::class, self::COMPONENT_WIDGETCOMPACT_HIGHLIGHTINFO],
            [self::class, self::COMPONENT_WIDGETCOMPACT_POSTINFO],
        );
    }

    public function getLayoutSubcomponents(array $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_WIDGET_CATEGORIES:
                $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::COMPONENT_LAYOUTWRAPPER_CATEGORIES];
                break;

            case self::COMPONENT_WIDGET_APPLIESTO:
                $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::COMPONENT_LAYOUTWRAPPER_APPLIESTO];
                break;

            case self::COMPONENT_WIDGETCOMPACT_GENERICINFO:
            case self::COMPONENT_WIDGETCOMPACT_HIGHLIGHTINFO:
                $ret[] = [PoP_Module_Processor_PublishedLayouts::class, PoP_Module_Processor_PublishedLayouts::COMPONENT_LAYOUT_WIDGETPUBLISHED];
                break;

            case self::COMPONENT_WIDGETCOMPACT_POSTINFO:
                $ret[] = [PoP_Module_Processor_PublishedLayouts::class, PoP_Module_Processor_PublishedLayouts::COMPONENT_LAYOUT_WIDGETPUBLISHED];
                if (PoP_ApplicationProcessors_Utils::addCategories()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::COMPONENT_LAYOUTWRAPPER_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::COMPONENT_LAYOUTWRAPPER_APPLIESTO];
                }
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $component, array &$props)
    {
        $titles = array(
            self::COMPONENT_WIDGET_CATEGORIES => TranslationAPIFacade::getInstance()->__('Categories', 'poptheme-wassup'),
            self::COMPONENT_WIDGET_APPLIESTO => TranslationAPIFacade::getInstance()->__('Applies to', 'poptheme-wassup'),
            self::COMPONENT_WIDGETCOMPACT_GENERICINFO => TranslationAPIFacade::getInstance()->__('Post', 'poptheme-wassup'),
            self::COMPONENT_WIDGETCOMPACT_HIGHLIGHTINFO => TranslationAPIFacade::getInstance()->__('Highlight', 'poptheme-wassup'),
            self::COMPONENT_WIDGETCOMPACT_POSTINFO => TranslationAPIFacade::getInstance()->__('Post', 'poptheme-wassup'),
        );

        return $titles[$component[1]] ?? null;
    }
    public function getFontawesome(array $component, array &$props)
    {
        $fontawesomes = array(
            self::COMPONENT_WIDGET_CATEGORIES => 'fa-info-circle',
            self::COMPONENT_WIDGET_APPLIESTO => 'fa-info-circle',
            self::COMPONENT_WIDGETCOMPACT_GENERICINFO => 'fa-info-circle',
            self::COMPONENT_WIDGETCOMPACT_HIGHLIGHTINFO => 'fa-bullseye',
            // self::COMPONENT_WIDGETCOMPACT_POSTINFO => 'fa-flash',
            self::COMPONENT_WIDGETCOMPACT_POSTINFO => 'fa-circle',
        );

        return $fontawesomes[$component[1]] ?? null;
    }

    public function getBodyClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGETCOMPACT_GENERICINFO:
            case self::COMPONENT_WIDGETCOMPACT_HIGHLIGHTINFO:
            case self::COMPONENT_WIDGETCOMPACT_POSTINFO:
                return 'list-group list-group-sm';
        }

        return parent::getBodyClass($component, $props);
    }
    public function getItemWrapper(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGETCOMPACT_GENERICINFO:
            case self::COMPONENT_WIDGETCOMPACT_HIGHLIGHTINFO:
            case self::COMPONENT_WIDGETCOMPACT_POSTINFO:
                return 'pop-hide-empty list-group-item';
        }

        return parent::getItemWrapper($component, $props);
    }
    public function getWidgetClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGETCOMPACT_GENERICINFO:
            case self::COMPONENT_WIDGETCOMPACT_HIGHLIGHTINFO:
            case self::COMPONENT_WIDGETCOMPACT_POSTINFO:
                return 'panel panel-default panel-sm';
        }

        return parent::getWidgetClass($component, $props);
    }
}



