<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_EM_Custom_Module_Processor_FormWidgets extends PoP_Module_Processor_WidgetsBase
{
    public final const COMPONENT_WIDGET_FORM_EVENTDETAILS = 'widget-form-event-details';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_WIDGET_FORM_EVENTDETAILS],
        );
    }

    public function getLayoutSubcomponents(array $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_WIDGET_FORM_EVENTDETAILS:
                if (PoP_ApplicationProcessors_Utils::addCategories()) {
                    $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_FORMINPUTGROUP_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_FORMINPUTGROUP_APPLIESTO];
                }
                $ret[] = [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::COMPONENT_FORMINPUTGROUP_DATERANGETIMEPICKER];
                $ret[] = [GD_EM_Module_Processor_FormComponentGroups::class, GD_EM_Module_Processor_FormComponentGroups::COMPONENT_EM_FORMCOMPONENTGROUP_SINGLELOCATIONTYPEAHEADMAP];

                // Only if the Volunteering is enabled
                if (defined('POP_VOLUNTEERING_ROUTE_VOLUNTEER') && POP_VOLUNTEERING_ROUTE_VOLUNTEER) {
                    $ret[] = [PoPTheme_Wassup_Module_Processor_FormGroups::class, PoPTheme_Wassup_Module_Processor_FormGroups::COMPONENT_FORMINPUTGROUP_VOLUNTEERSNEEDED_SELECT];
                }
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $component, array &$props)
    {
        $titles = array(
            self::COMPONENT_WIDGET_FORM_EVENTDETAILS => TranslationAPIFacade::getInstance()->__('Event details', 'poptheme-wassup'),
        );

        return $titles[$component[1]] ?? null;
    }

    public function getWidgetClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGET_FORM_EVENTDETAILS:
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
            case self::COMPONENT_WIDGET_FORM_EVENTDETAILS:
                return 'panel-body';
        }

        return parent::getBodyClass($component, $props);
    }
    public function getItemWrapper(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGET_FORM_EVENTDETAILS:
                return '';
        }

        return parent::getItemWrapper($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGET_FORM_EVENTDETAILS:
                // Typeahead map: make it small
                $this->setProp([PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, PoP_Module_Processor_SelectableTypeaheadMapFormComponents::COMPONENT_EM_FORMCOMPONENT_SINGLELOCATIONTYPEAHEADMAP], $props, 'wrapper-class', '');
                $this->setProp([PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, PoP_Module_Processor_SelectableTypeaheadMapFormComponents::COMPONENT_EM_FORMCOMPONENT_SINGLELOCATIONTYPEAHEADMAP], $props, 'map-class', 'spacing-bottom-md');
                $this->setProp([PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, PoP_Module_Processor_SelectableTypeaheadMapFormComponents::COMPONENT_EM_FORMCOMPONENT_SINGLELOCATIONTYPEAHEADMAP], $props, 'typeahead-class', '');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



