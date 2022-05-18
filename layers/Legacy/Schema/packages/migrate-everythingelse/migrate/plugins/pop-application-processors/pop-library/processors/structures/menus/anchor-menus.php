<?php

class PoP_Module_Processor_AnchorMenus extends PoP_Module_Processor_AnchorMenusBase
{
    public final const MODULE_ANCHORMENU = 'anchormenu';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ANCHORMENU],
        );
    }

    public function getItemClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_ANCHORMENU:
                return 'btn btn-default btn-block';
        }
    
        return parent::getItemClass($componentVariation, $props);
    }
}


