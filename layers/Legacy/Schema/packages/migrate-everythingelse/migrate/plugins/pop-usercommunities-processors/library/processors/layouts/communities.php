<?php

class GD_URE_Module_Processor_UserCommunityLayouts extends GD_URE_Module_Processor_UserCommunityLayoutsBase
{
    public final const COMPONENT_URE_LAYOUT_COMMUNITIES = 'ure-layoutuser-communities';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_URE_LAYOUT_COMMUNITIES],
        );
    }

    public function getLayoutSubcomponents(array $component)
    {
        $ret = parent::getLayoutSubcomponents($component);
    
        switch ($component[1]) {
            case self::COMPONENT_URE_LAYOUT_COMMUNITIES:
                $ret[] = [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::COMPONENT_LAYOUT_MULTIPLEUSER_ADDONS];
                break;
        }
        
        return $ret;
    }
}



