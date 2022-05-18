<?php

class GD_EM_Module_Processor_CreateUpdatePostFormInners extends Wassup_Module_Processor_CreateUpdatePostFormInnersBase
{
    public final const MODULE_FORMINNER_EVENT = 'forminner-event';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINNER_EVENT],
        );
    }

    protected function volunteering(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINNER_EVENT:
                return true;
        }

        return parent::volunteering($component);
    }
    protected function getLocationsInput(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINNER_EVENT:
                return [PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, PoP_Module_Processor_SelectableTypeaheadMapFormComponents::COMPONENT_EM_FORMCOMPONENT_SINGLELOCATIONTYPEAHEADMAP];
        }

        return parent::getLocationsInput($component);
    }

    public function getLayoutSubmodules(array $component)
    {

        // Comment Leo 03/04/2015: IMPORTANT!
        // For the _wpnonce and the pid, get the value from the queryhandler when editing
        // Why? because otherwise, if first loading an Edit Discussion (eg: http://m3l.localhost/edit-discussion/?_wpnonce=e88efa07c5&pid=17887)
        // being the user logged out and only then he log in, the refetchBlock doesn't work because it doesn't have the pid/_wpnonce values
        // Adding it through QueryInputOutputHandler EditPost allows us to have it there always, even if the post was not loaded since the user has no access to it
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_FORMINNER_EVENT:
                return array_merge(
                    $ret,
                    array(
                        [Wassup_Module_Processor_FormMultipleComponents::class, Wassup_Module_Processor_FormMultipleComponents::COMPONENT_MULTICOMPONENT_FORM_LEFTSIDE],
                        [GD_EM_Custom_Module_Processor_FormMultipleComponents::class, GD_EM_Custom_Module_Processor_FormMultipleComponents::COMPONENT_MULTICOMPONENT_FORM_EVENT_RIGHTSIDE],
                    )
                );
        }

        return parent::getComponentSubmodules($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINNER_EVENT:
                $this->setProp([PoP_Module_Processor_DateRangeComponentInputs::class, PoP_Module_Processor_DateRangeComponentInputs::COMPONENT_FORMINPUT_DATERANGETIMEPICKER], $props, 'daterange-class', 'opens-left');

                // Make it into left/right columns
                $rightsides = array(
                    self::COMPONENT_FORMINNER_EVENT => [GD_EM_Custom_Module_Processor_FormMultipleComponents::class, GD_EM_Custom_Module_Processor_FormMultipleComponents::COMPONENT_MULTICOMPONENT_FORM_EVENT_RIGHTSIDE],
                );
                $leftside = $this->isLink($component) ?
                    [Wassup_Module_Processor_FormMultipleComponents::class, Wassup_Module_Processor_FormMultipleComponents::COMPONENT_MULTICOMPONENT_FORM_LINK_LEFTSIDE] :
                    [Wassup_Module_Processor_FormMultipleComponents::class, Wassup_Module_Processor_FormMultipleComponents::COMPONENT_MULTICOMPONENT_FORM_LEFTSIDE];
                if (!($form_left_class = $this->getProp($component, $props, 'form-left-class')/*$this->get_general_prop($props, 'form-left-class')*/)) {
                    $form_left_class = 'col-sm-8';
                }
                if (!($form_right_class = $this->getProp($component, $props, 'form-right-class')/*$this->get_general_prop($props, 'form-right-class')*/)) {
                    $form_right_class = 'col-sm-4';
                }
                $this->appendProp($leftside, $props, 'class', $form_left_class);
                $this->appendProp($rightsides[$component[1]], $props, 'class', $form_right_class);
                break;
        }

        parent::initModelProps($component, $props);
    }
}



