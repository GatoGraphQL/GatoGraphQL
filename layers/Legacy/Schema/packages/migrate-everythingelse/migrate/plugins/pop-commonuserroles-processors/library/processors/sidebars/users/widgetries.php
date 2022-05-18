<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Custom_Module_Processor_UserWidgets extends PoP_Module_Processor_WidgetsBase
{
    public final const MODULE_WIDGETCOMPACT_ORGANIZATIONINFO = 'widgetcompact-organization-info';
    public final const MODULE_WIDGETCOMPACT_INDIVIDUALINFO = 'widgetcompact-individual-info';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WIDGETCOMPACT_ORGANIZATIONINFO],
            [self::class, self::MODULE_WIDGETCOMPACT_INDIVIDUALINFO],
        );
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_WIDGETCOMPACT_ORGANIZATIONINFO:
                $ret[] = [GD_URE_Custom_Module_Processor_SidebarComponentsWrappers::class, GD_URE_Custom_Module_Processor_SidebarComponentsWrappers::MODULE_URE_LAYOUTWRAPPER_PROFILEORGANIZATION_DETAILS];
                if (defined('POP_LOCATIONSPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_USERSIDEBARLOCATIONS];
                }
                $ret[] = [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_USERCOMPACT];
                break;

            case self::MODULE_WIDGETCOMPACT_INDIVIDUALINFO:
                $ret[] = [GD_URE_Custom_Module_Processor_SidebarComponentsWrappers::class, GD_URE_Custom_Module_Processor_SidebarComponentsWrappers::MODULE_URE_LAYOUTWRAPPER_PROFILEINDIVIDUAL_DETAILS];
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
            self::MODULE_WIDGETCOMPACT_ORGANIZATIONINFO => TranslationAPIFacade::getInstance()->__('Organization', 'poptheme-wassup'),
            self::MODULE_WIDGETCOMPACT_INDIVIDUALINFO => TranslationAPIFacade::getInstance()->__('Individual', 'poptheme-wassup'),
        );

        return $titles[$componentVariation[1]] ?? null;
    }
    public function getFontawesome(array $componentVariation, array &$props)
    {
        $fontawesomes = array(
            self::MODULE_WIDGETCOMPACT_ORGANIZATIONINFO => getRouteIcon(POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS, false),
            self::MODULE_WIDGETCOMPACT_INDIVIDUALINFO => getRouteIcon(POP_COMMONUSERROLES_ROUTE_INDIVIDUALS, false),
        );

        return $fontawesomes[$componentVariation[1]] ?? null;
    }

    public function getBodyClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGETCOMPACT_ORGANIZATIONINFO:
            case self::MODULE_WIDGETCOMPACT_INDIVIDUALINFO:
                return 'list-group list-group-sm';
        }

        return parent::getBodyClass($componentVariation, $props);
    }
    public function getItemWrapper(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGETCOMPACT_ORGANIZATIONINFO:
            case self::MODULE_WIDGETCOMPACT_INDIVIDUALINFO:
                return 'pop-hide-empty list-group-item';
        }

        return parent::getItemWrapper($componentVariation, $props);
    }
    public function getWidgetClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGETCOMPACT_ORGANIZATIONINFO:
            case self::MODULE_WIDGETCOMPACT_INDIVIDUALINFO:
                // return 'panel panel-info panel-sm';
                return 'panel panel-default panel-sm';
        }

        return parent::getWidgetClass($componentVariation, $props);
    }
}



