<?php

class PoP_Module_Processor_AnchorMenus extends PoP_Module_Processor_AnchorMenusBase
{
    public const MODULE_ANCHORMENU = 'anchormenu';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ANCHORMENU],
        );
    }

    public function getItemClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORMENU:
                return 'btn btn-default btn-block';
        }
    
        return parent::getItemClass($module, $props);
    }
}


