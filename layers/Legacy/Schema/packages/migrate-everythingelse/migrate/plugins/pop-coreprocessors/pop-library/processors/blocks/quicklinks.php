<?php

class PoP_Module_Processor_QuicklinksBlocks extends PoP_Module_Processor_BlocksBase
{
    public final const MODULE_BLOCK_EVERYTHING_QUICKLINKS = 'block-everything-quicklinks';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_EVERYTHING_QUICKLINKS],
        );
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);
        
        switch ($module[1]) {
            case self::MODULE_BLOCK_EVERYTHING_QUICKLINKS:
                $ret[] = [PoP_Core_Module_Processor_Forms::class, PoP_Core_Module_Processor_Forms::MODULE_FORM_EVERYTHINGQUICKLINKS];
                break;
        }
    
        return $ret;
    }
}



