<?php

class PoP_Module_Processor_PostMapScriptCustomizations extends PoP_Module_Processor_PostMapScriptCustomizationsBase
{
    public const MODULE_MAP_SCRIPTCUSTOMIZATION_POST = 'em-map-scriptcustomization-post';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MAP_SCRIPTCUSTOMIZATION_POST],
        );
    }
}


