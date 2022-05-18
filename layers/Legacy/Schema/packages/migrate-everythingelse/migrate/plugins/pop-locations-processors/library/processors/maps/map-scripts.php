<?php

class PoP_Module_Processor_MapScripts extends PoP_Module_Processor_MapScriptsBase
{
    public final const MODULE_MAP_SCRIPT = 'em-map-script';
    public final const MODULE_MAP_SCRIPT_POST = 'em-map-script-post';
    public final const MODULE_MAP_SCRIPT_USER = 'em-map-script-user';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MAP_SCRIPT],
            [self::class, self::MODULE_MAP_SCRIPT_POST],
            [self::class, self::MODULE_MAP_SCRIPT_USER],
        );
    }

    public function getCustomizationSubmodule(array $module)
    {
        $customizations = array(
            self::MODULE_MAP_SCRIPT_POST => [PoP_Module_Processor_PostMapScriptCustomizations::class, PoP_Module_Processor_PostMapScriptCustomizations::MODULE_MAP_SCRIPTCUSTOMIZATION_POST],
            self::MODULE_MAP_SCRIPT_USER => [PoP_Module_Processor_UserMapScriptCustomizations::class, PoP_Module_Processor_UserMapScriptCustomizations::MODULE_MAP_SCRIPTCUSTOMIZATION_USER],
        );

        if ($customization = $customizations[$module[1]] ?? null) {
            return $customization;
        }

        return parent::getCustomizationSubmodule($module);
    }
}


