<?php

class GD_EM_Module_Processor_UrlParamTextFormInputs extends PoP_Module_Processor_UrlParamTextFormInputsBase
{
    public final const COMPONENT_FORMINPUT_URLPARAMTEXT_LOCATIONID = 'forminput-urlparam-locationid';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_URLPARAMTEXT_LOCATIONID,
        );
    }

    public function getName(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_URLPARAMTEXT_LOCATIONID:
                return POP_INPUTNAME_LOCATIONID;
        }

        return parent::getName($component);
    }
}



