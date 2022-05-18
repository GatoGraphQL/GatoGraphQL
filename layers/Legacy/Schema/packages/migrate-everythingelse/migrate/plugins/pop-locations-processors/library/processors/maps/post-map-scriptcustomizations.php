<?php

class PoP_Module_Processor_PostMapScriptCustomizations extends PoP_Module_Processor_PostMapScriptCustomizationsBase
{
    public final const MODULE_MAP_SCRIPTCUSTOMIZATION_POST = 'em-map-scriptcustomization-post';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MAP_SCRIPTCUSTOMIZATION_POST],
        );
    }
}


