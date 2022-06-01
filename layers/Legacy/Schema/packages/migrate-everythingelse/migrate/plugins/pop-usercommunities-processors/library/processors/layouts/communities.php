<?php

class GD_URE_Module_Processor_UserCommunityLayouts extends GD_URE_Module_Processor_UserCommunityLayoutsBase
{
    public final const COMPONENT_URE_LAYOUT_COMMUNITIES = 'ure-layoutuser-communities';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_URE_LAYOUT_COMMUNITIES,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);
    
        switch ($component->name) {
            case self::COMPONENT_URE_LAYOUT_COMMUNITIES:
                $ret[] = [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::COMPONENT_LAYOUT_MULTIPLEUSER_ADDONS];
                break;
        }
        
        return $ret;
    }
}



