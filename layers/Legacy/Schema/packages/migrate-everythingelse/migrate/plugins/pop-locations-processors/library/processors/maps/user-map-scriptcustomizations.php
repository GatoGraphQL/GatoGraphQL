<?php

class PoP_Module_Processor_UserMapScriptCustomizations extends PoP_Module_Processor_UserMapScriptCustomizationsBase
{
    public final const MODULE_MAP_SCRIPTCUSTOMIZATION_USER = 'em-map-scriptcustomization-user';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MAP_SCRIPTCUSTOMIZATION_USER],
        );
    }
}


