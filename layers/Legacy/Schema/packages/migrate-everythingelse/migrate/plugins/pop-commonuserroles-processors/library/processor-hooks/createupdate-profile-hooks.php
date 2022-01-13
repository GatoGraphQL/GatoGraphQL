<?php

class PoP_CommonUserRolesProcessors_CreateUpdateProfileHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'GD_CreateUpdate_ProfileOrganization_Trait:form-inputs',
            array($this, 'getProfileorganizationFormInputs')
        );
        \PoP\Root\App::addFilter(
            'GD_CreateUpdate_ProfileIndividual_Trait:form-inputs',
            array($this, 'getProfileindividualFormInputs')
        );
    }

    public function getProfileorganizationFormInputs($inputs = array())
    {
        return array_merge(
            $inputs,
            array(
                'organizationtypes' => [GD_URE_Module_Processor_MultiSelectFormInputs::class, GD_URE_Module_Processor_MultiSelectFormInputs::MODULE_URE_FORMINPUT_ORGANIZATIONTYPES],
                'organizationcategories' => [GD_URE_Module_Processor_MultiSelectFormInputs::class, GD_URE_Module_Processor_MultiSelectFormInputs::MODULE_URE_FORMINPUT_ORGANIZATIONCATEGORIES],
                'contact_number' => [GD_URE_Module_Processor_TextFormInputs::class, GD_URE_Module_Processor_TextFormInputs::MODULE_URE_FORMINPUT_CUP_CONTACTNUMBER],
                'contact_person' => [GD_URE_Module_Processor_TextFormInputs::class, GD_URE_Module_Processor_TextFormInputs::MODULE_URE_FORMINPUT_CUP_CONTACTPERSON],
            )
        );
    }

    public function getProfileindividualFormInputs($inputs = array())
    {
        return array_merge(
            $inputs,
            array(
                'last_name' => [GD_URE_Module_Processor_TextFormInputs::class, GD_URE_Module_Processor_TextFormInputs::MODULE_URE_FORMINPUT_CUP_LASTNAME],
                'individualinterests' => [GD_URE_Module_Processor_MultiSelectFormInputs::class, GD_URE_Module_Processor_MultiSelectFormInputs::MODULE_URE_FORMINPUT_INDIVIDUALINTERESTS],
            )
        );
    }
}


/**
 * Initialization
 */
new PoP_CommonUserRolesProcessors_CreateUpdateProfileHooks();
