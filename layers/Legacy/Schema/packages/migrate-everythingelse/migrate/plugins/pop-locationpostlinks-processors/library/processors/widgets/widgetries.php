<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_LocationPostLinks_Module_Processor_FormWidgets extends PoP_Module_Processor_WidgetsBase
{
    public final const MODULE_WIDGET_FORM_LOCATIONPOSTLINKDETAILS = 'widget-form-locationpostlinkdetails';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_WIDGET_FORM_LOCATIONPOSTLINKDETAILS],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_WIDGET_FORM_LOCATIONPOSTLINKDETAILS:
                // $ret[] = [GD_Custom_EM_Module_Processor_FormGroups::class, GD_Custom_EM_Module_Processor_FormGroups::COMPONENT_FORMINPUTGROUP_LOCATIONPOSTCATEGORIES];
                if (PoP_ApplicationProcessors_Utils::addCategories()) {
                    $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_FORMINPUTGROUP_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_FORMINPUTGROUP_APPLIESTO];
                }
                $ret[] = [GD_EM_Module_Processor_FormComponentGroups::class, GD_EM_Module_Processor_FormComponentGroups::COMPONENT_EM_FORMCOMPONENTGROUP_TYPEAHEADMAP];

                // Only if the Volunteering is enabled
                if (defined('POP_VOLUNTEERING_ROUTE_VOLUNTEER') && POP_VOLUNTEERING_ROUTE_VOLUNTEER) {
                    $ret[] = [PoPTheme_Wassup_Module_Processor_FormGroups::class, PoPTheme_Wassup_Module_Processor_FormGroups::COMPONENT_FORMINPUTGROUP_VOLUNTEERSNEEDED_SELECT];
                }

                // Comment Leo 16/01/2016: There's no need to ask for the LinkAccess since we don't show it anyway
                // if ($component == [self::class, self::COMPONENT_WIDGET_FORM_LOCATIONPOSTLINKDETAILS]) {
                //     $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKACCESS];
                // }
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $component, array &$props)
    {
        $locationpost = PoP_LocationPosts_PostNameUtils::getNameUc();
        $titles = array(
            self::COMPONENT_WIDGET_FORM_LOCATIONPOSTLINKDETAILS => sprintf(TranslationAPIFacade::getInstance()->__('%s link details', 'pop-locationpostlinks-processors'), $locationpost),
        );

        return $titles[$component[1]] ?? null;
    }

    public function getWidgetClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGET_FORM_LOCATIONPOSTLINKDETAILS:
                if ($class = $this->getProp($component, $props, 'form-widget-class')/*$this->get_general_prop($props, 'form-widget-class')*/) {
                    return $class;
                }

                return 'panel panel-info';
        }

        return parent::getWidgetClass($component, $props);
    }

    public function getBodyClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGET_FORM_LOCATIONPOSTLINKDETAILS:
                return 'panel-body';
        }

        return parent::getBodyClass($component, $props);
    }
    public function getItemWrapper(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGET_FORM_LOCATIONPOSTLINKDETAILS:
                return '';
        }

        return parent::getItemWrapper($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGET_FORM_LOCATIONPOSTLINKDETAILS:
                // Typeahead map: make it small
                $this->setProp([PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, PoP_Module_Processor_SelectableTypeaheadMapFormComponents::COMPONENT_EM_FORMCOMPONENT_TYPEAHEADMAP], $props, 'wrapper-class', '');
                $this->setProp([PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, PoP_Module_Processor_SelectableTypeaheadMapFormComponents::COMPONENT_EM_FORMCOMPONENT_TYPEAHEADMAP], $props, 'map-class', 'spacing-bottom-md');
                $this->setProp([PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, PoP_Module_Processor_SelectableTypeaheadMapFormComponents::COMPONENT_EM_FORMCOMPONENT_TYPEAHEADMAP], $props, 'typeahead-class', '');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



