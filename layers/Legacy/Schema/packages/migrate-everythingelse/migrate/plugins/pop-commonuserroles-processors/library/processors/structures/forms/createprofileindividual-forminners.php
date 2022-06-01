<?php

class GD_URE_Module_Processor_CreateProfileIndividualFormInners extends GD_URE_Module_Processor_CreateProfileIndividualFormInnersBase
{
    public final const COMPONENT_FORMINNER_PROFILEINDIVIDUAL_CREATE = 'forminner-profileindividual-create';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINNER_PROFILEINDIVIDUAL_CREATE,
        );
    }
}



