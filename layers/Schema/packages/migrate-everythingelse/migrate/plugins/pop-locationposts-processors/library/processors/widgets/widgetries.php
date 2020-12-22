<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class GD_Custom_EM_Module_Processor_FormWidgets extends PoP_Module_Processor_WidgetsBase
{
    public const MODULE_WIDGET_FORM_LOCATIONPOSTDETAILS = 'widget-form-locationpostdetails';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WIDGET_FORM_LOCATIONPOSTDETAILS],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);
    
        switch ($module[1]) {
            case self::MODULE_WIDGET_FORM_LOCATIONPOSTDETAILS:
                if (PoP_ApplicationProcessors_Utils::addCategories()) {
                    $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FORMINPUTGROUP_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FORMINPUTGROUP_APPLIESTO];
                }
                $ret[] = [GD_EM_Module_Processor_FormComponentGroups::class, GD_EM_Module_Processor_FormComponentGroups::MODULE_EM_FORMCOMPONENTGROUP_TYPEAHEADMAP];
                
                // Only if the Volunteering is enabled
                if (defined('POP_VOLUNTEERING_ROUTE_VOLUNTEER') && POP_VOLUNTEERING_ROUTE_VOLUNTEER) {
                    $ret[] = [PoPTheme_Wassup_Module_Processor_FormGroups::class, PoPTheme_Wassup_Module_Processor_FormGroups::MODULE_FORMINPUTGROUP_VOLUNTEERSNEEDED_SELECT];
                }
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $module, array &$props)
    {
        $locationpost = PoP_LocationPosts_PostNameUtils::getNameUc();
        $titles = array(
            self::MODULE_WIDGET_FORM_LOCATIONPOSTDETAILS => sprintf(TranslationAPIFacade::getInstance()->__('%s details', 'pop-locationposts-processors'), $locationpost),
        );

        return $titles[$module[1]];
    }

    public function getWidgetClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_FORM_LOCATIONPOSTDETAILS:
                if ($class = $this->getProp($module, $props, 'form-widget-class')/*$this->get_general_prop($props, 'form-widget-class')*/) {
                    return $class;
                }

                return 'panel panel-info';
        }

        return parent::getWidgetClass($module, $props);
    }

    public function getBodyClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_FORM_LOCATIONPOSTDETAILS:
                return 'panel-body';
        }

        return parent::getBodyClass($module, $props);
    }
    public function getItemWrapper(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_FORM_LOCATIONPOSTDETAILS:
                return '';
        }

        return parent::getItemWrapper($module, $props);
    }

    public function initModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_FORM_LOCATIONPOSTDETAILS:
                // Typeahead map: make it small
                $this->setProp([PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, PoP_Module_Processor_SelectableTypeaheadMapFormComponents::MODULE_EM_FORMCOMPONENT_TYPEAHEADMAP], $props, 'wrapper-class', '');
                $this->setProp([PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, PoP_Module_Processor_SelectableTypeaheadMapFormComponents::MODULE_EM_FORMCOMPONENT_TYPEAHEADMAP], $props, 'map-class', 'spacing-bottom-md');
                $this->setProp([PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, PoP_Module_Processor_SelectableTypeaheadMapFormComponents::MODULE_EM_FORMCOMPONENT_TYPEAHEADMAP], $props, 'typeahead-class', '');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



