<?php

class PoP_Module_Processor_CustomSettingsBlocks extends PoP_Module_Processor_BlocksBase
{
    public const MODULE_BLOCK_SETTINGS = 'block-settings';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_SETTINGS],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_BLOCK_SETTINGS => POP_USERPLATFORM_ROUTE_SETTINGS,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_BLOCK_SETTINGS:
                $ret[] = [PoP_Module_Processor_CustomSettingsDataloads::class, PoP_Module_Processor_CustomSettingsDataloads::MODULE_DATALOAD_SETTINGS];
                break;
        }

        return $ret;
    }
}



