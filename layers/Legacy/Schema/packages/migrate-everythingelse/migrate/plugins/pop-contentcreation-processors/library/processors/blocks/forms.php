<?php

class PoP_ContentCreation_Module_Processor_Blocks extends PoP_Module_Processor_FormBlocksBase
{
    public final const COMPONENT_BLOCK_FLAG = 'block-flag';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BLOCK_FLAG,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_BLOCK_FLAG => POP_CONTENTCREATION_ROUTE_FLAG,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_BLOCK_FLAG:
                $ret[] = [PoP_ContentCreation_Module_Processor_Dataloads::class, PoP_ContentCreation_Module_Processor_Dataloads::COMPONENT_DATALOAD_FLAG];
                break;
        }
    
        return $ret;
    }
}



