<?php

class PoP_Module_Processor_PostMapScriptCustomizations extends PoP_Module_Processor_PostMapScriptCustomizationsBase
{
    public final const COMPONENT_MAP_SCRIPTCUSTOMIZATION_POST = 'em-map-scriptcustomization-post';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MAP_SCRIPTCUSTOMIZATION_POST,
        );
    }
}


