<?php

class GD_URE_Module_Processor_CreateProfileIndividualFormInners extends GD_URE_Module_Processor_CreateProfileIndividualFormInnersBase
{
    public final const MODULE_FORMINNER_PROFILEINDIVIDUAL_CREATE = 'forminner-profileindividual-create';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINNER_PROFILEINDIVIDUAL_CREATE],
        );
    }
}



