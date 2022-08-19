<?php

class GD_URE_Module_Processor_UpdateProfileOrganizationFormInners extends GD_URE_Module_Processor_UpdateProfileOrganizationFormInnersBase
{
    public final const COMPONENT_FORMINNER_PROFILEORGANIZATION_UPDATE = 'forminner-profileorganization-update';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINNER_PROFILEORGANIZATION_UPDATE,
        );
    }
}



