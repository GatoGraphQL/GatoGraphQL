<?php

class GD_URE_Module_Processor_UpdateProfileIndividualFormInners extends GD_URE_Module_Processor_UpdateProfileIndividualFormInnersBase
{
    public final const COMPONENT_FORMINNER_PROFILEINDIVIDUAL_UPDATE = 'forminner-profileindividual-update';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINNER_PROFILEINDIVIDUAL_UPDATE,
        );
    }
}



