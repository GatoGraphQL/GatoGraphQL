<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_EventLinksCreation_Custom_Module_Processor_FormWidgets extends PoP_Module_Processor_WidgetsBase
{
    public final const MODULE_WIDGET_FORM_EVENTLINKDETAILS = 'widget-form-eventlink-details';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WIDGET_FORM_EVENTLINKDETAILS],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_WIDGET_FORM_EVENTLINKDETAILS:
                if (PoP_ApplicationProcessors_Utils::addCategories()) {
                    $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FORMINPUTGROUP_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FORMINPUTGROUP_APPLIESTO];
                }
                $ret[] = [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::MODULE_FORMINPUTGROUP_DATERANGETIMEPICKER];
                $ret[] = [GD_EM_Module_Processor_FormComponentGroups::class, GD_EM_Module_Processor_FormComponentGroups::MODULE_EM_FORMCOMPONENTGROUP_SINGLELOCATIONTYPEAHEADMAP];

                // Only if the Volunteering is enabled
                if (defined('POP_VOLUNTEERING_ROUTE_VOLUNTEER') && POP_VOLUNTEERING_ROUTE_VOLUNTEER) {
                    $ret[] = [PoPTheme_Wassup_Module_Processor_FormGroups::class, PoPTheme_Wassup_Module_Processor_FormGroups::MODULE_FORMINPUTGROUP_VOLUNTEERSNEEDED_SELECT];
                }

                // Comment Leo 16/01/2016: There's no need to ask for the LinkAccess since we don't show it anyway
                // if ($module == [self::class, self::MODULE_WIDGET_FORM_EVENTLINKDETAILS]) {
                //     $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKACCESS];
                // }
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $module, array &$props)
    {
        $titles = array(
            self::MODULE_WIDGET_FORM_EVENTLINKDETAILS => TranslationAPIFacade::getInstance()->__('Event link details', 'poptheme-wassup'),
        );

        return $titles[$module[1]] ?? null;
    }

    public function getWidgetClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_FORM_EVENTLINKDETAILS:
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
            case self::MODULE_WIDGET_FORM_EVENTLINKDETAILS:
                return 'panel-body';
        }

        return parent::getBodyClass($module, $props);
    }
    public function getItemWrapper(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_FORM_EVENTLINKDETAILS:
                return '';
        }

        return parent::getItemWrapper($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_FORM_EVENTLINKDETAILS:
                // Typeahead map: make it small
                $this->setProp([PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, PoP_Module_Processor_SelectableTypeaheadMapFormComponents::MODULE_EM_FORMCOMPONENT_SINGLELOCATIONTYPEAHEADMAP], $props, 'wrapper-class', '');
                $this->setProp([PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, PoP_Module_Processor_SelectableTypeaheadMapFormComponents::MODULE_EM_FORMCOMPONENT_SINGLELOCATIONTYPEAHEADMAP], $props, 'map-class', 'spacing-bottom-md');
                $this->setProp([PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, PoP_Module_Processor_SelectableTypeaheadMapFormComponents::MODULE_EM_FORMCOMPONENT_SINGLELOCATIONTYPEAHEADMAP], $props, 'typeahead-class', '');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



