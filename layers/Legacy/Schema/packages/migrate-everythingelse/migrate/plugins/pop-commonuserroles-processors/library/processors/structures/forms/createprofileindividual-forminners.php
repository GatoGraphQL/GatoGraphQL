<?php

class GD_URE_Module_Processor_CreateProfileIndividualFormInners extends GD_URE_Module_Processor_CreateProfileIndividualFormInnersBase
{
    public final const COMPONENT_FORMINNER_PROFILEINDIVIDUAL_CREATE = 'forminner-profileindividual-create';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINNER_PROFILEINDIVIDUAL_CREATE],
        );
    }
}



