<?php

class PoP_SocialNetwork_Module_Processor_Blocks extends PoP_Module_Processor_FormBlocksBase
{
    public final const MODULE_BLOCK_CONTACTUSER = 'block-contactuser';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_CONTACTUSER],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::MODULE_BLOCK_CONTACTUSER => POP_SOCIALNETWORK_ROUTE_CONTACTUSER,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_BLOCK_CONTACTUSER:
                $ret[] = [PoP_SocialNetwork_Module_Processor_Dataloads::class, PoP_SocialNetwork_Module_Processor_Dataloads::MODULE_DATALOAD_CONTACTUSER];
                break;
        }
    
        return $ret;
    }
}



