<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoPTheme_Wassup_EM_AE_Module_Processor_Widgets extends PoP_Module_Processor_WidgetsBase
{
    public const MODULE_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO = 'em-widgetcompact-automatedemails-eventinfo';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO:
                $ret[] = [GD_EM_Module_Processor_DateTimeLayouts::class, GD_EM_Module_Processor_DateTimeLayouts::MODULE_EM_LAYOUT_DATETIME];
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTSIDEBARLOCATIONS];
                $ret[] = [PoPTheme_Wassup_EM_AE_Module_Processor_QuicklinkGroups::class, PoPTheme_Wassup_EM_AE_Module_Processor_QuicklinkGroups::MODULE_QUICKLINKGROUP_EVENTBOTTOM];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $module, array &$props)
    {
        $titles = array(
            self::MODULE_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO => TranslationAPIFacade::getInstance()->__('Event', 'poptheme-wassup'),
        );

        return $titles[$module[1]] ?? null;
    }
    public function getFontawesome(array $module, array &$props)
    {
        $fontawesomes = array(
            self::MODULE_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO => 'fa-calendar',
        );

        return $fontawesomes[$module[1]] ?? null;
    }
    public function getBodyClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO:
                return 'list-group list-group-sm';
        }

        return parent::getBodyClass($module, $props);
    }
    public function getItemWrapper(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO:
                return 'pop-hide-empty list-group-item';
        }

        return parent::getItemWrapper($module, $props);
    }
    public function getWidgetClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO:
                return 'panel panel-default panel-sm';
        }

        return parent::getWidgetClass($module, $props);
    }
}


