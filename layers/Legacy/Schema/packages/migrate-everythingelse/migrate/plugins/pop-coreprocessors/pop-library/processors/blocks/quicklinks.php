<?php

class PoP_Module_Processor_QuicklinksBlocks extends PoP_Module_Processor_BlocksBase
{
    public final const MODULE_BLOCK_EVERYTHING_QUICKLINKS = 'block-everything-quicklinks';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_EVERYTHING_QUICKLINKS],
        );
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);
        
        switch ($component[1]) {
            case self::MODULE_BLOCK_EVERYTHING_QUICKLINKS:
                $ret[] = [PoP_Core_Module_Processor_Forms::class, PoP_Core_Module_Processor_Forms::MODULE_FORM_EVERYTHINGQUICKLINKS];
                break;
        }
    
        return $ret;
    }
}



