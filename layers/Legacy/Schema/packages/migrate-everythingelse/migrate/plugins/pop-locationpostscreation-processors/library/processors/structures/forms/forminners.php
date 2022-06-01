<?php

class GD_Custom_EM_Module_Processor_CreateUpdatePostFormInners extends Wassup_Module_Processor_CreateUpdatePostFormInnersBase
{
    public final const COMPONENT_FORMINNER_LOCATIONPOST = 'forminner-locationpost';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINNER_LOCATIONPOST],
        );
    }
    protected function volunteering(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINNER_LOCATIONPOST:
                return true;
        }

        return parent::volunteering($component);
    }
    protected function getLocationsInput(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINNER_LOCATIONPOST:
                return [PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, PoP_Module_Processor_SelectableTypeaheadMapFormComponents::COMPONENT_EM_FORMCOMPONENT_TYPEAHEADMAP];
        }

        return parent::getLocationsInput($component);
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {

        // Comment Leo 03/04/2015: IMPORTANT!
        // For the _wpnonce and the pid, get the value from the queryhandler when editing
        // Why? because otherwise, if first loading an Edit Discussion (eg: http://m3l.localhost/edit-discussion/?_wpnonce=e88efa07c5&pid=17887)
        // being the user logged out and only then he log in, the refetchBlock doesn't work because it doesn't have the pid/_wpnonce values
        // Adding it through QueryInputOutputHandler EditPost allows us to have it there always, even if the post was not loaded since the user has no access to it
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_FORMINNER_LOCATIONPOST:
                return array_merge(
                    $ret,
                    array(
                        [Wassup_Module_Processor_FormMultipleComponents::class, Wassup_Module_Processor_FormMultipleComponents::COMPONENT_MULTICOMPONENT_FORM_LEFTSIDE],
                        [GD_Custom_EM_Module_Processor_FormMultipleComponents::class, GD_Custom_EM_Module_Processor_FormMultipleComponents::COMPONENT_MULTICOMPONENT_FORM_LOCATIONPOST_RIGHTSIDE],
                    )
                );
        }

        return $ret;
    }

    protected function getEditorInitialvalue(\PoP\ComponentModel\Component\Component $component)
    {

        // Allow RIPESS Asia to set an initial value for the Add Project form
        switch ($component->name) {
            case self::COMPONENT_FORMINNER_LOCATIONPOST:
                return \PoP\Root\App::applyFilters('GD_Custom_Module_Processor_CreateUpdatePostFormInners:editor_initialvalue', null, $component);
        }

        return parent::getEditorInitialvalue($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINNER_LOCATIONPOST:
                // Make it into left/right columns
                $rightsides = array(
                    self::COMPONENT_FORMINNER_LOCATIONPOST => [GD_Custom_EM_Module_Processor_FormMultipleComponents::class, GD_Custom_EM_Module_Processor_FormMultipleComponents::COMPONENT_MULTICOMPONENT_FORM_LOCATIONPOST_RIGHTSIDE],
                );

                $leftside = [Wassup_Module_Processor_FormMultipleComponents::class, Wassup_Module_Processor_FormMultipleComponents::COMPONENT_MULTICOMPONENT_FORM_LEFTSIDE];

                // Allow to override the classes, so it can be set for the Addons pageSection without the col-sm styles, but one on top of the other
                if (!($form_left_class = $this->getProp($component, $props, 'form-left-class')/*$this->get_general_prop($props, 'form-left-class')*/)) {
                    $form_left_class = 'col-sm-8';
                }
                if (!($form_right_class = $this->getProp($component, $props, 'form-right-class')/*$this->get_general_prop($props, 'form-right-class')*/)) {
                    $form_right_class = 'col-sm-4';
                }
                $this->appendProp($leftside, $props, 'class', $form_left_class);
                $this->appendProp($rightsides[$component->name], $props, 'class', $form_right_class);
                break;
        }

        parent::initModelProps($component, $props);
    }
}



