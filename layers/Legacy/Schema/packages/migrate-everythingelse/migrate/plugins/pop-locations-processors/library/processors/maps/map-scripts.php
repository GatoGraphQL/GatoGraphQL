<?php

class PoP_Module_Processor_MapScripts extends PoP_Module_Processor_MapScriptsBase
{
    public final const COMPONENT_MAP_SCRIPT = 'em-map-script';
    public final const COMPONENT_MAP_SCRIPT_POST = 'em-map-script-post';
    public final const COMPONENT_MAP_SCRIPT_USER = 'em-map-script-user';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MAP_SCRIPT],
            [self::class, self::COMPONENT_MAP_SCRIPT_POST],
            [self::class, self::COMPONENT_MAP_SCRIPT_USER],
        );
    }

    public function getCustomizationSubcomponent(array $component)
    {
        $customizations = array(
            self::COMPONENT_MAP_SCRIPT_POST => [PoP_Module_Processor_PostMapScriptCustomizations::class, PoP_Module_Processor_PostMapScriptCustomizations::COMPONENT_MAP_SCRIPTCUSTOMIZATION_POST],
            self::COMPONENT_MAP_SCRIPT_USER => [PoP_Module_Processor_UserMapScriptCustomizations::class, PoP_Module_Processor_UserMapScriptCustomizations::COMPONENT_MAP_SCRIPTCUSTOMIZATION_USER],
        );

        if ($customization = $customizations[$component[1]] ?? null) {
            return $customization;
        }

        return parent::getCustomizationSubcomponent($component);
    }
}


