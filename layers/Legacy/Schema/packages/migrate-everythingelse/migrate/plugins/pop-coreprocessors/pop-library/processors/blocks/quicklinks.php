<?php

class PoP_Module_Processor_QuicklinksBlocks extends PoP_Module_Processor_BlocksBase
{
    public final const COMPONENT_BLOCK_EVERYTHING_QUICKLINKS = 'block-everything-quicklinks';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_EVERYTHING_QUICKLINKS],
        );
    }

    protected function getInnerSubcomponents(array $component): array
    {
        $ret = parent::getInnerSubcomponents($component);
        
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_EVERYTHING_QUICKLINKS:
                $ret[] = [PoP_Core_Module_Processor_Forms::class, PoP_Core_Module_Processor_Forms::COMPONENT_FORM_EVERYTHINGQUICKLINKS];
                break;
        }
    
        return $ret;
    }
}



