<?php

class GD_EM_Module_Processor_InputGroupFormComponents extends PoP_Module_Processor_InputGroupFormComponentsBase
{
    public final const COMPONENT_FORMCOMPONENT_INPUTGROUP_TYPEAHEADADDLOCATION = 'formcomponent-inputgroup-typeaheadaddlocation';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMCOMPONENT_INPUTGROUP_TYPEAHEADADDLOCATION],
        );
    }

    public function getInputSubcomponent(array $component)
    {
        $ret = parent::getInputSubcomponent($component);

        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_INPUTGROUP_TYPEAHEADADDLOCATION:
                return [GD_EM_Module_Processor_TextFormInputs::class, GD_EM_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_TEXT_TYPEAHEADADDLOCATION];
        }

        return $ret;
    }

    public function getControlSubcomponents(array $component)
    {
        $ret = parent::getControlSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_INPUTGROUP_TYPEAHEADADDLOCATION:
                // Allow PoP Add Locations Processors to inject the "+" button
                if ($control = \PoP\Root\App::applyFilters(
                    'GD_EM_Module_Processor_InputGroupFormComponents:control-layout',
                    null,
                    $component
                )
                ) {
                    $ret[] = $control;
                }
                break;
        }

        return $ret;
    }
}



