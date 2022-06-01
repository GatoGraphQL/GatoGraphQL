<?php

class PoP_Share_Module_Processor_Blocks extends PoP_Module_Processor_FormBlocksBase
{
    public final const COMPONENT_BLOCK_SHAREBYEMAIL = 'block-sharebyemail';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BLOCK_SHAREBYEMAIL,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_BLOCK_SHAREBYEMAIL => POP_SHARE_ROUTE_SHAREBYEMAIL,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_BLOCK_SHAREBYEMAIL:
                $ret[] = [PoP_Share_Module_Processor_Dataloads::class, PoP_Share_Module_Processor_Dataloads::COMPONENT_DATALOAD_SHAREBYEMAIL];
                break;
        }
    
        return $ret;
    }
}


