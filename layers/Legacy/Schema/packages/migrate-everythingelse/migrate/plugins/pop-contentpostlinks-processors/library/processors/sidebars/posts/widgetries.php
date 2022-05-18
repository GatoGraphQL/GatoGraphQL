<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentPostLinks_Module_Processor_CustomPostWidgets extends PoP_Module_Processor_WidgetsBase
{
    public final const MODULE_WIDGET_LINK_ACCESS = 'widget-link-access';
    public final const MODULE_WIDGET_LINK_CATEGORIES = 'widget-link-categories';
    public final const MODULE_WIDGETCOMPACT_LINKINFO = 'widgetcompact-link-info';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WIDGET_LINK_ACCESS],
            [self::class, self::MODULE_WIDGET_LINK_CATEGORIES],
            [self::class, self::MODULE_WIDGETCOMPACT_LINKINFO],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_WIDGET_LINK_ACCESS:
                $ret[] = [PoP_ContentPostLinks_Module_Processor_Layouts::class, PoP_ContentPostLinks_Module_Processor_Layouts::MODULE_LAYOUT_LINK_ACCESS];
                break;

            case self::MODULE_WIDGET_LINK_CATEGORIES:
                $ret[] = [PoP_ContentPostLinks_Module_Processor_WidgetWrappers::class, PoP_ContentPostLinks_Module_Processor_WidgetWrappers::MODULE_LAYOUTWRAPPER_LINK_CATEGORIES];
                break;

            case self::MODULE_WIDGETCOMPACT_LINKINFO:
                if (PoP_ApplicationProcessors_Utils::addCategories()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::MODULE_LAYOUTWRAPPER_CATEGORIES];
                }
                $ret[] = [PoP_ContentPostLinks_Module_Processor_Layouts::class, PoP_ContentPostLinks_Module_Processor_Layouts::MODULE_LAYOUT_LINK_ACCESS];
                $ret[] = [PoP_Module_Processor_PublishedLayouts::class, PoP_Module_Processor_PublishedLayouts::MODULE_LAYOUT_WIDGETPUBLISHED];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $module, array &$props)
    {
        $titles = array(
            self::MODULE_WIDGET_LINK_ACCESS => TranslationAPIFacade::getInstance()->__('Access type', 'poptheme-wassup'),
            self::MODULE_WIDGET_LINK_CATEGORIES => TranslationAPIFacade::getInstance()->__('Categories', 'poptheme-wassup'),
            self::MODULE_WIDGETCOMPACT_LINKINFO => TranslationAPIFacade::getInstance()->__('Link', 'poptheme-wassup'),
        );

        return $titles[$module[1]] ?? null;
    }
    public function getFontawesome(array $module, array &$props)
    {
        $fontawesomes = array(
            self::MODULE_WIDGET_LINK_ACCESS => 'fa-link',
            self::MODULE_WIDGET_LINK_CATEGORIES => 'fa-info-circle',
            self::MODULE_WIDGETCOMPACT_LINKINFO => 'fa-link',
        );

        return $fontawesomes[$module[1]] ?? null;
    }

    public function getBodyClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGETCOMPACT_LINKINFO:
                return 'list-group list-group-sm';
        }

        return parent::getBodyClass($module, $props);
    }
    public function getItemWrapper(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGETCOMPACT_LINKINFO:
                return 'pop-hide-empty list-group-item';
        }

        return parent::getItemWrapper($module, $props);
    }
    public function getWidgetClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGETCOMPACT_LINKINFO:
                return 'panel panel-default panel-sm';
        }

        return parent::getWidgetClass($module, $props);
    }
}



