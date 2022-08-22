<?php

class GD_EM_Module_Processor_CreateLocationForms extends PoP_Module_Processor_FormsBase
{
    public final const COMPONENT_FORM_CREATELOCATION = 'em-form-createlocation';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORM_CREATELOCATION,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORM_CREATELOCATION:
                return [GD_EM_Module_Processor_CreateLocationFormInners::class, GD_EM_Module_Processor_CreateLocationFormInners::COMPONENT_FORMINNER_CREATELOCATION];
        }

        return parent::getInnerSubcomponent($component);
    }
}



