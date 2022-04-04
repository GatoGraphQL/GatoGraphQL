<?php

class PoP_SocialNetwork_Module_Processor_Blocks extends PoP_Module_Processor_FormBlocksBase
{
    public final const MODULE_BLOCK_CONTACTUSER = 'block-contactuser';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_CONTACTUSER],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_BLOCK_CONTACTUSER => POP_SOCIALNETWORK_ROUTE_CONTACTUSER,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_BLOCK_CONTACTUSER:
                $ret[] = [PoP_SocialNetwork_Module_Processor_Dataloads::class, PoP_SocialNetwork_Module_Processor_Dataloads::MODULE_DATALOAD_CONTACTUSER];
                break;
        }
    
        return $ret;
    }
}



