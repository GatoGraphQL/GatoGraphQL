<?php

class GD_URE_Module_Processor_UpdateProfileForms extends PoP_Module_Processor_FormsBase
{
    public final const COMPONENT_FORM_PROFILEORGANIZATION_UPDATE = 'form-profileorganization-update';
    public final const COMPONENT_FORM_PROFILEINDIVIDUAL_UPDATE = 'form-profileindividual-update';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORM_PROFILEORGANIZATION_UPDATE,
            self::COMPONENT_FORM_PROFILEINDIVIDUAL_UPDATE,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORM_PROFILEORGANIZATION_UPDATE:
                return \PoP\Root\App::applyFilters(
                    'GD_URE_Module_Processor_UpdateProfileForms:getInnerSubcomponent:profileorganization', 
                    [GD_URE_Module_Processor_UpdateProfileOrganizationFormInners::class, GD_URE_Module_Processor_UpdateProfileOrganizationFormInners::COMPONENT_FORMINNER_PROFILEORGANIZATION_UPDATE]
                );

            case self::COMPONENT_FORM_PROFILEINDIVIDUAL_UPDATE:
                return \PoP\Root\App::applyFilters(
                    'GD_URE_Module_Processor_UpdateProfileForms:getInnerSubcomponent:profileindividual', 
                    [GD_URE_Module_Processor_UpdateProfileIndividualFormInners::class, GD_URE_Module_Processor_UpdateProfileIndividualFormInners::COMPONENT_FORMINNER_PROFILEINDIVIDUAL_UPDATE]
                );
        }

        return parent::getInnerSubcomponent($component);
    }
}



