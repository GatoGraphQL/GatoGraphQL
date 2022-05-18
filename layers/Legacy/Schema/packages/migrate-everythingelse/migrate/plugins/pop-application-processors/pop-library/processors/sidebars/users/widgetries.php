<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\ModuleConfiguration as UsersModuleConfiguration;

class GD_Custom_Module_Processor_UserWidgets extends PoP_Module_Processor_WidgetsBase
{
    public final const MODULE_WIDGETCOMPACT_GENERICUSERINFO = 'widgetcompact-genericuserinfo';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WIDGETCOMPACT_GENERICUSERINFO],
        );
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_WIDGETCOMPACT_GENERICUSERINFO:
                if (defined('POP_LOCATIONSPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_USERSIDEBARLOCATIONS];
                }
                $ret[] = [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_USERCOMPACT];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $componentVariation, array &$props)
    {
        $titles = array(
            self::MODULE_WIDGETCOMPACT_GENERICUSERINFO => TranslationAPIFacade::getInstance()->__('User', 'poptheme-wassup'),
        );

        return $titles[$componentVariation[1]] ?? null;
    }
    public function getFontawesome(array $componentVariation, array &$props)
    {
        $fontawesomes = array(
            self::MODULE_WIDGETCOMPACT_GENERICUSERINFO => getRouteIcon(UsersModuleConfiguration::getUsersRoute(), false),
        );

        return $fontawesomes[$componentVariation[1]] ?? null;
    }

    public function getBodyClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGETCOMPACT_GENERICUSERINFO:
                return 'list-group list-group-sm';
        }

        return parent::getBodyClass($componentVariation, $props);
    }
    public function getItemWrapper(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGETCOMPACT_GENERICUSERINFO:
                return 'pop-hide-empty list-group-item';
        }

        return parent::getItemWrapper($componentVariation, $props);
    }
    public function getWidgetClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGETCOMPACT_GENERICUSERINFO:
                // return 'panel panel-info panel-sm';
                return 'panel panel-default panel-sm';
        }

        return parent::getWidgetClass($componentVariation, $props);
    }
}



