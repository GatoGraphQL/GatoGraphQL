<?php

class PoP_Volunteering_Module_Processor_Blocks extends PoP_Module_Processor_FormBlocksBase
{
    public const MODULE_BLOCK_VOLUNTEER = 'block-volunteer';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_VOLUNTEER],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_BLOCK_VOLUNTEER => POP_VOLUNTEERING_ROUTE_VOLUNTEER,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_BLOCK_VOLUNTEER:
                $ret[] = [PoP_Volunteering_Module_Processor_Dataloads::class, PoP_Volunteering_Module_Processor_Dataloads::MODULE_DATALOAD_VOLUNTEER];
                break;
        }
    
        return $ret;
    }
}



