<?php

class PoP_SocialNetwork_Module_Processor_Blocks extends PoP_Module_Processor_FormBlocksBase
{
    public final const COMPONENT_BLOCK_CONTACTUSER = 'block-contactuser';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BLOCK_CONTACTUSER,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_BLOCK_CONTACTUSER => POP_SOCIALNETWORK_ROUTE_CONTACTUSER,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_BLOCK_CONTACTUSER:
                $ret[] = [PoP_SocialNetwork_Module_Processor_Dataloads::class, PoP_SocialNetwork_Module_Processor_Dataloads::COMPONENT_DATALOAD_CONTACTUSER];
                break;
        }
    
        return $ret;
    }
}



