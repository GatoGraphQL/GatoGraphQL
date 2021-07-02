<?php

class PoP_Module_Processor_InputGroupFormComponents extends PoP_Module_Processor_InputGroupFormComponentsBase
{
    public const MODULE_FORMCOMPONENT_INPUTGROUP_TYPEAHEADSEARCH = 'formcomponent-inputgroup-typeaheadsearch';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_INPUTGROUP_TYPEAHEADSEARCH],
        );
    }

    public function getInputSubmodule(array $module)
    {
        $ret = parent::getInputSubmodule($module);

        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_INPUTGROUP_TYPEAHEADSEARCH:
                return [PoP_Module_Processor_TypeaheadTextFormInputs::class, PoP_Module_Processor_TypeaheadTextFormInputs::MODULE_FORMINPUT_TEXT_TYPEAHEADSEARCH];
        }

        return $ret;
    }

    public function getControlSubmodules(array $module)
    {
        $ret = parent::getControlSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_INPUTGROUP_TYPEAHEADSEARCH:
                $ret[] = [PoP_Module_Processor_TypeaheadButtonControls::class, PoP_Module_Processor_TypeaheadButtonControls::MODULE_BUTTONCONTROL_TYPEAHEADSEARCH];
                break;
        }

        return $ret;
    }
}



