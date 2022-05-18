<?php

class GD_EM_Module_Processor_InputGroupFormComponents extends PoP_Module_Processor_InputGroupFormComponentsBase
{
    public final const MODULE_FORMCOMPONENT_INPUTGROUP_TYPEAHEADADDLOCATION = 'formcomponent-inputgroup-typeaheadaddlocation';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_INPUTGROUP_TYPEAHEADADDLOCATION],
        );
    }

    public function getInputSubmodule(array $componentVariation)
    {
        $ret = parent::getInputSubmodule($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENT_INPUTGROUP_TYPEAHEADADDLOCATION:
                return [GD_EM_Module_Processor_TextFormInputs::class, GD_EM_Module_Processor_TextFormInputs::MODULE_FORMINPUT_TEXT_TYPEAHEADADDLOCATION];
        }

        return $ret;
    }

    public function getControlSubmodules(array $componentVariation)
    {
        $ret = parent::getControlSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENT_INPUTGROUP_TYPEAHEADADDLOCATION:
                // Allow PoP Add Locations Processors to inject the "+" button
                if ($control = \PoP\Root\App::applyFilters(
                    'GD_EM_Module_Processor_InputGroupFormComponents:control-layout',
                    null,
                    $componentVariation
                )
                ) {
                    $ret[] = $control;
                }
                break;
        }

        return $ret;
    }
}



