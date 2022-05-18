<?php

class GD_EM_Module_Processor_MapInners extends GD_EM_Module_Processor_MapInnersBase
{
    public final const MODULE_EM_MAPINNER_POST = 'em-mapinner-post';
    public final const MODULE_EM_MAPINNER_USER = 'em-mapinner-user';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_EM_MAPINNER_POST],
            [self::class, self::COMPONENT_EM_MAPINNER_USER],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_EM_MAPINNER_POST:
                $ret[] = [PoP_Module_Processor_MapScripts::class, PoP_Module_Processor_MapScripts::COMPONENT_MAP_SCRIPT_POST];
                break;

            case self::COMPONENT_EM_MAPINNER_USER:
                $ret[] = [PoP_Module_Processor_MapScripts::class, PoP_Module_Processor_MapScripts::COMPONENT_MAP_SCRIPT_USER];
                break;
        }

        return $ret;
    }
}



