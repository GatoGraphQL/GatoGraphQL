<?php

use PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade;

class PoP_UserAvatarProcessors_Module_Processor_UserBlocks extends PoP_Module_Processor_BlocksBase
{
    public const MODULE_BLOCK_USERAVATAR_UPDATE = 'block-useravatar-update';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_USERAVATAR_UPDATE],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_BLOCK_USERAVATAR_UPDATE => POP_USERAVATAR_ROUTE_EDITAVATAR,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_BLOCK_USERAVATAR_UPDATE:
                // Either with or without moduleAtts
                $pop_module_routemoduleprocessor_manager = RouteModuleProcessorManagerFacade::getInstance();
                $ret[] = $pop_module_routemoduleprocessor_manager->getRouteModuleByMostAllmatchingVarsProperties(POP_PAGEMODULEGROUPPLACEHOLDER_MAINCONTENTMODULE);
                break;
        }
    
        return $ret;
    }
}



