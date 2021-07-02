<?php

class PoP_Share_Module_Processor_Blocks extends PoP_Module_Processor_FormBlocksBase
{
    public const MODULE_BLOCK_SHAREBYEMAIL = 'block-sharebyemail';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_SHAREBYEMAIL],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_BLOCK_SHAREBYEMAIL => POP_SHARE_ROUTE_SHAREBYEMAIL,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_BLOCK_SHAREBYEMAIL:
                $ret[] = [PoP_Share_Module_Processor_Dataloads::class, PoP_Share_Module_Processor_Dataloads::MODULE_DATALOAD_SHAREBYEMAIL];
                break;
        }
    
        return $ret;
    }
}


