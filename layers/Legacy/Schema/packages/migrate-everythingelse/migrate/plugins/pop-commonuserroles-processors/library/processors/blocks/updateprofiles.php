<?php

class GD_URE_Module_Processor_UpdateProfileBlocks extends PoP_Module_Processor_UpdateProfileBlocksBase
{
    public const MODULE_BLOCK_PROFILEORGANIZATION_UPDATE = 'block-profileorganization-update';
    public const MODULE_BLOCK_PROFILEINDIVIDUAL_UPDATE = 'block-profileindividual-update';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_PROFILEORGANIZATION_UPDATE],
            [self::class, self::MODULE_BLOCK_PROFILEINDIVIDUAL_UPDATE],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_BLOCK_PROFILEINDIVIDUAL_UPDATE => POP_COMMONUSERROLES_ROUTE_EDITPROFILEINDIVIDUAL,
            self::MODULE_BLOCK_PROFILEORGANIZATION_UPDATE => POP_COMMONUSERROLES_ROUTE_EDITPROFILEORGANIZATION,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_BLOCK_PROFILEORGANIZATION_UPDATE:
                $ret[] = [GD_URE_Module_Processor_UpdateProfileDataloads::class, GD_URE_Module_Processor_UpdateProfileDataloads::MODULE_DATALOAD_PROFILEORGANIZATION_UPDATE];
                break;

            case self::MODULE_BLOCK_PROFILEINDIVIDUAL_UPDATE:
                $ret[] = [GD_URE_Module_Processor_UpdateProfileDataloads::class, GD_URE_Module_Processor_UpdateProfileDataloads::MODULE_DATALOAD_PROFILEINDIVIDUAL_UPDATE];
                break;
        }
    
        return $ret;
    }
}



