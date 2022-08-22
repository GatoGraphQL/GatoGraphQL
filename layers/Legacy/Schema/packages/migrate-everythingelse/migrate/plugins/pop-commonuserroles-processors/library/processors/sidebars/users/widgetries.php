<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Custom_Module_Processor_UserWidgets extends PoP_Module_Processor_WidgetsBase
{
    public final const COMPONENT_WIDGETCOMPACT_ORGANIZATIONINFO = 'widgetcompact-organization-info';
    public final const COMPONENT_WIDGETCOMPACT_INDIVIDUALINFO = 'widgetcompact-individual-info';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_WIDGETCOMPACT_ORGANIZATIONINFO,
            self::COMPONENT_WIDGETCOMPACT_INDIVIDUALINFO,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_WIDGETCOMPACT_ORGANIZATIONINFO:
                $ret[] = [GD_URE_Custom_Module_Processor_SidebarComponentsWrappers::class, GD_URE_Custom_Module_Processor_SidebarComponentsWrappers::COMPONENT_URE_LAYOUTWRAPPER_PROFILEORGANIZATION_DETAILS];
                if (defined('POP_LOCATIONSPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_USERSIDEBARLOCATIONS];
                }
                $ret[] = [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_USERCOMPACT];
                break;

            case self::COMPONENT_WIDGETCOMPACT_INDIVIDUALINFO:
                $ret[] = [GD_URE_Custom_Module_Processor_SidebarComponentsWrappers::class, GD_URE_Custom_Module_Processor_SidebarComponentsWrappers::COMPONENT_URE_LAYOUTWRAPPER_PROFILEINDIVIDUAL_DETAILS];
                if (defined('POP_LOCATIONSPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_USERSIDEBARLOCATIONS];
                }
                $ret[] = [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_USERCOMPACT];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $titles = array(
            self::COMPONENT_WIDGETCOMPACT_ORGANIZATIONINFO => TranslationAPIFacade::getInstance()->__('Organization', 'poptheme-wassup'),
            self::COMPONENT_WIDGETCOMPACT_INDIVIDUALINFO => TranslationAPIFacade::getInstance()->__('Individual', 'poptheme-wassup'),
        );

        return $titles[$component->name] ?? null;
    }
    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $fontawesomes = array(
            self::COMPONENT_WIDGETCOMPACT_ORGANIZATIONINFO => getRouteIcon(POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS, false),
            self::COMPONENT_WIDGETCOMPACT_INDIVIDUALINFO => getRouteIcon(POP_COMMONUSERROLES_ROUTE_INDIVIDUALS, false),
        );

        return $fontawesomes[$component->name] ?? null;
    }

    public function getBodyClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_WIDGETCOMPACT_ORGANIZATIONINFO:
            case self::COMPONENT_WIDGETCOMPACT_INDIVIDUALINFO:
                return 'list-group list-group-sm';
        }

        return parent::getBodyClass($component, $props);
    }
    public function getItemWrapper(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_WIDGETCOMPACT_ORGANIZATIONINFO:
            case self::COMPONENT_WIDGETCOMPACT_INDIVIDUALINFO:
                return 'pop-hide-empty list-group-item';
        }

        return parent::getItemWrapper($component, $props);
    }
    public function getWidgetClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_WIDGETCOMPACT_ORGANIZATIONINFO:
            case self::COMPONENT_WIDGETCOMPACT_INDIVIDUALINFO:
                // return 'panel panel-info panel-sm';
                return 'panel panel-default panel-sm';
        }

        return parent::getWidgetClass($component, $props);
    }
}



