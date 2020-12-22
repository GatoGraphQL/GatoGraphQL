<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class GD_Custom_Module_Processor_UserWidgets extends PoP_Module_Processor_WidgetsBase
{
    public const MODULE_WIDGETCOMPACT_GENERICUSERINFO = 'widgetcompact-genericuserinfo';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WIDGETCOMPACT_GENERICUSERINFO],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);
    
        switch ($module[1]) {
            case self::MODULE_WIDGETCOMPACT_GENERICUSERINFO:
                if (defined('POP_LOCATIONSPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_USERSIDEBARLOCATIONS];
                }
                $ret[] = [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_USERCOMPACT];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $module, array &$props)
    {
        $titles = array(
            self::MODULE_WIDGETCOMPACT_GENERICUSERINFO => TranslationAPIFacade::getInstance()->__('User', 'poptheme-wassup'),
        );

        return $titles[$module[1]];
    }
    public function getFontawesome(array $module, array &$props)
    {
        $fontawesomes = array(
            self::MODULE_WIDGETCOMPACT_GENERICUSERINFO => getRouteIcon(POP_USERS_ROUTE_USERS, false),
        );

        return $fontawesomes[$module[1]];
    }

    public function getBodyClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGETCOMPACT_GENERICUSERINFO:
                return 'list-group list-group-sm';
        }

        return parent::getBodyClass($module, $props);
    }
    public function getItemWrapper(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGETCOMPACT_GENERICUSERINFO:
                return 'pop-hide-empty list-group-item';
        }

        return parent::getItemWrapper($module, $props);
    }
    public function getWidgetClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGETCOMPACT_GENERICUSERINFO:
                // return 'panel panel-info panel-sm';
                return 'panel panel-default panel-sm';
        }

        return parent::getWidgetClass($module, $props);
    }
}



