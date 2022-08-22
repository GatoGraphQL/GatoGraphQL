<?php

class GD_URE_Module_Processor_CreateProfileOrganizationFormInners extends GD_URE_Module_Processor_CreateProfileOrganizationFormInnersBase
{
    public final const COMPONENT_FORMINNER_PROFILEORGANIZATION_CREATE = 'forminner-profileorganization-create';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINNER_PROFILEORGANIZATION_CREATE,
        );
    }
}



