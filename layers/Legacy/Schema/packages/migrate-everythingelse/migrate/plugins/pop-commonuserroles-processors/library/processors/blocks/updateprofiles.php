<?php

class GD_URE_Module_Processor_UpdateProfileBlocks extends PoP_Module_Processor_UpdateProfileBlocksBase
{
    public final const MODULE_BLOCK_PROFILEORGANIZATION_UPDATE = 'block-profileorganization-update';
    public final const MODULE_BLOCK_PROFILEINDIVIDUAL_UPDATE = 'block-profileindividual-update';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_PROFILEORGANIZATION_UPDATE],
            [self::class, self::MODULE_BLOCK_PROFILEINDIVIDUAL_UPDATE],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_BLOCK_PROFILEINDIVIDUAL_UPDATE => POP_COMMONUSERROLES_ROUTE_EDITPROFILEINDIVIDUAL,
            self::MODULE_BLOCK_PROFILEORGANIZATION_UPDATE => POP_COMMONUSERROLES_ROUTE_EDITPROFILEORGANIZATION,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        switch ($componentVariation[1]) {
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



