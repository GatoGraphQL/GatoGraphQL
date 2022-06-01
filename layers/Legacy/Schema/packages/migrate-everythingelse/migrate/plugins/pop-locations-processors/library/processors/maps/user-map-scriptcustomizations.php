<?php

class PoP_Module_Processor_UserMapScriptCustomizations extends PoP_Module_Processor_UserMapScriptCustomizationsBase
{
    public final const COMPONENT_MAP_SCRIPTCUSTOMIZATION_USER = 'em-map-scriptcustomization-user';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MAP_SCRIPTCUSTOMIZATION_USER,
        );
    }
}


