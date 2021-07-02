<?php

class PoP_ContactUs_Module_Processor_Blocks extends PoP_Module_Processor_FormBlocksBase
{
    public const MODULE_BLOCK_CONTACTUS = 'block-contactus';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_CONTACTUS],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_BLOCK_CONTACTUS => POP_CONTACTUS_ROUTE_CONTACTUS,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_BLOCK_CONTACTUS:
                $ret[] = [PoP_ContactUs_Module_Processor_Dataloads::class, PoP_ContactUs_Module_Processor_Dataloads::MODULE_DATALOAD_CONTACTUS];
                break;
        }
    
        return $ret;
    }
}


