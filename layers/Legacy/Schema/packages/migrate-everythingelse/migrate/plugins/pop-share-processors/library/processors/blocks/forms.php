<?php

class PoP_Share_Module_Processor_Blocks extends PoP_Module_Processor_FormBlocksBase
{
    public final const COMPONENT_BLOCK_SHAREBYEMAIL = 'block-sharebyemail';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_SHAREBYEMAIL],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_SHAREBYEMAIL => POP_SHARE_ROUTE_SHAREBYEMAIL,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponents(array $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_BLOCK_SHAREBYEMAIL:
                $ret[] = [PoP_Share_Module_Processor_Dataloads::class, PoP_Share_Module_Processor_Dataloads::COMPONENT_DATALOAD_SHAREBYEMAIL];
                break;
        }
    
        return $ret;
    }
}


