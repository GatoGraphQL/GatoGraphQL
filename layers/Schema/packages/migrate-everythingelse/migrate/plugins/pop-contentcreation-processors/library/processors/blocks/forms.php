<?php

class PoP_ContentCreation_Module_Processor_Blocks extends PoP_Module_Processor_FormBlocksBase
{
    public const MODULE_BLOCK_FLAG = 'block-flag';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_FLAG],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_BLOCK_FLAG => POP_CONTENTCREATION_ROUTE_FLAG,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_BLOCK_FLAG:
                $ret[] = [PoP_ContentCreation_Module_Processor_Dataloads::class, PoP_ContentCreation_Module_Processor_Dataloads::MODULE_DATALOAD_FLAG];
                break;
        }
    
        return $ret;
    }
}



