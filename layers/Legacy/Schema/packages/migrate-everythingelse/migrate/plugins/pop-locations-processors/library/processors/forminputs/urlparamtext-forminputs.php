<?php

class GD_EM_Module_Processor_UrlParamTextFormInputs extends PoP_Module_Processor_UrlParamTextFormInputsBase
{
    public final const COMPONENT_FORMINPUT_URLPARAMTEXT_LOCATIONID = 'forminput-urlparam-locationid';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_URLPARAMTEXT_LOCATIONID],
        );
    }

    public function getName(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_URLPARAMTEXT_LOCATIONID:
                return POP_INPUTNAME_LOCATIONID;
        }

        return parent::getName($component);
    }
}



