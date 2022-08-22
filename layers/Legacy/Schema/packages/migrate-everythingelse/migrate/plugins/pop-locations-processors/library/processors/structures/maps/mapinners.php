<?php

class GD_EM_Module_Processor_MapInners extends GD_EM_Module_Processor_MapInnersBase
{
    public final const COMPONENT_EM_MAPINNER_POST = 'em-mapinner-post';
    public final const COMPONENT_EM_MAPINNER_USER = 'em-mapinner-user';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_EM_MAPINNER_POST,
            self::COMPONENT_EM_MAPINNER_USER,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
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



