<?php

class GD_EM_Module_Processor_CreateLocationForms extends PoP_Module_Processor_FormsBase
{
    public final const COMPONENT_FORM_CREATELOCATION = 'em-form-createlocation';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORM_CREATELOCATION],
        );
    }

    public function getInnerSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORM_CREATELOCATION:
                return [GD_EM_Module_Processor_CreateLocationFormInners::class, GD_EM_Module_Processor_CreateLocationFormInners::COMPONENT_FORMINNER_CREATELOCATION];
        }

        return parent::getInnerSubcomponent($component);
    }
}



