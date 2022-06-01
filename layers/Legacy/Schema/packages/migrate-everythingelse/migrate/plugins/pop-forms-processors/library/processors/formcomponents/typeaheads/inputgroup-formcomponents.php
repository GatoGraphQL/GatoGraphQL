<?php

class PoP_Module_Processor_InputGroupFormComponents extends PoP_Module_Processor_InputGroupFormComponentsBase
{
    public final const COMPONENT_FORMCOMPONENT_INPUTGROUP_TYPEAHEADSEARCH = 'formcomponent-inputgroup-typeaheadsearch';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMCOMPONENT_INPUTGROUP_TYPEAHEADSEARCH,
        );
    }

    public function getInputSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getInputSubcomponent($component);

        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_INPUTGROUP_TYPEAHEADSEARCH:
                return [PoP_Module_Processor_TypeaheadTextFormInputs::class, PoP_Module_Processor_TypeaheadTextFormInputs::COMPONENT_FORMINPUT_TEXT_TYPEAHEADSEARCH];
        }

        return $ret;
    }

    public function getControlSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getControlSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_INPUTGROUP_TYPEAHEADSEARCH:
                $ret[] = [PoP_Module_Processor_TypeaheadButtonControls::class, PoP_Module_Processor_TypeaheadButtonControls::COMPONENT_BUTTONCONTROL_TYPEAHEADSEARCH];
                break;
        }

        return $ret;
    }
}



