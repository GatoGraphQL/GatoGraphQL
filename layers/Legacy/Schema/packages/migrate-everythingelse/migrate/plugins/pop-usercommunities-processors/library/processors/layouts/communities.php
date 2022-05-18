<?php

class GD_URE_Module_Processor_UserCommunityLayouts extends GD_URE_Module_Processor_UserCommunityLayoutsBase
{
    public final const MODULE_URE_LAYOUT_COMMUNITIES = 'ure-layoutuser-communities';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_LAYOUT_COMMUNITIES],
        );
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);
    
        switch ($componentVariation[1]) {
            case self::MODULE_URE_LAYOUT_COMMUNITIES:
                $ret[] = [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::MODULE_LAYOUT_MULTIPLEUSER_ADDONS];
                break;
        }
        
        return $ret;
    }
}



