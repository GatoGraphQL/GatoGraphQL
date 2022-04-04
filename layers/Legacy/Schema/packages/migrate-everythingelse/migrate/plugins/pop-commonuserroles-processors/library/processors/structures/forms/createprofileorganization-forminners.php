<?php

class GD_URE_Module_Processor_CreateProfileOrganizationFormInners extends GD_URE_Module_Processor_CreateProfileOrganizationFormInnersBase
{
    public final const MODULE_FORMINNER_PROFILEORGANIZATION_CREATE = 'forminner-profileorganization-create';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINNER_PROFILEORGANIZATION_CREATE],
        );
    }
}



