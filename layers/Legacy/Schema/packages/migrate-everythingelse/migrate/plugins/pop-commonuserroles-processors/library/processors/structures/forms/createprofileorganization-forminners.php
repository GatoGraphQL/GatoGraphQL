<?php

class GD_URE_Module_Processor_CreateProfileOrganizationFormInners extends GD_URE_Module_Processor_CreateProfileOrganizationFormInnersBase
{
    public final const COMPONENT_FORMINNER_PROFILEORGANIZATION_CREATE = 'forminner-profileorganization-create';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINNER_PROFILEORGANIZATION_CREATE],
        );
    }
}



