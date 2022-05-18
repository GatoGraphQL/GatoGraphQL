<?php

class GD_URE_Module_Processor_UpdateProfileForms extends PoP_Module_Processor_FormsBase
{
    public final const MODULE_FORM_PROFILEORGANIZATION_UPDATE = 'form-profileorganization-update';
    public final const MODULE_FORM_PROFILEINDIVIDUAL_UPDATE = 'form-profileindividual-update';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORM_PROFILEORGANIZATION_UPDATE],
            [self::class, self::MODULE_FORM_PROFILEINDIVIDUAL_UPDATE],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORM_PROFILEORGANIZATION_UPDATE:
                return \PoP\Root\App::applyFilters(
                    'GD_URE_Module_Processor_UpdateProfileForms:getInnerSubmodule:profileorganization', 
                    [GD_URE_Module_Processor_UpdateProfileOrganizationFormInners::class, GD_URE_Module_Processor_UpdateProfileOrganizationFormInners::MODULE_FORMINNER_PROFILEORGANIZATION_UPDATE]
                );

            case self::MODULE_FORM_PROFILEINDIVIDUAL_UPDATE:
                return \PoP\Root\App::applyFilters(
                    'GD_URE_Module_Processor_UpdateProfileForms:getInnerSubmodule:profileindividual', 
                    [GD_URE_Module_Processor_UpdateProfileIndividualFormInners::class, GD_URE_Module_Processor_UpdateProfileIndividualFormInners::MODULE_FORMINNER_PROFILEINDIVIDUAL_UPDATE]
                );
        }

        return parent::getInnerSubmodule($componentVariation);
    }
}



