<?php

class GD_URE_Module_Processor_UpdateProfileIndividualFormInners extends GD_URE_Module_Processor_UpdateProfileIndividualFormInnersBase
{
    public final const MODULE_FORMINNER_PROFILEINDIVIDUAL_UPDATE = 'forminner-profileindividual-update';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINNER_PROFILEINDIVIDUAL_UPDATE],
        );
    }
}



