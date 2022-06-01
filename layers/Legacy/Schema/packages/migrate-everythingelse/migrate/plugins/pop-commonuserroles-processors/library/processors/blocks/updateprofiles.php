<?php

class GD_URE_Module_Processor_UpdateProfileBlocks extends PoP_Module_Processor_UpdateProfileBlocksBase
{
    public final const COMPONENT_BLOCK_PROFILEORGANIZATION_UPDATE = 'block-profileorganization-update';
    public final const COMPONENT_BLOCK_PROFILEINDIVIDUAL_UPDATE = 'block-profileindividual-update';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_PROFILEORGANIZATION_UPDATE],
            [self::class, self::COMPONENT_BLOCK_PROFILEINDIVIDUAL_UPDATE],
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_BLOCK_PROFILEINDIVIDUAL_UPDATE => POP_COMMONUSERROLES_ROUTE_EDITPROFILEINDIVIDUAL,
            self::COMPONENT_BLOCK_PROFILEORGANIZATION_UPDATE => POP_COMMONUSERROLES_ROUTE_EDITPROFILEORGANIZATION,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_BLOCK_PROFILEORGANIZATION_UPDATE:
                $ret[] = [GD_URE_Module_Processor_UpdateProfileDataloads::class, GD_URE_Module_Processor_UpdateProfileDataloads::COMPONENT_DATALOAD_PROFILEORGANIZATION_UPDATE];
                break;

            case self::COMPONENT_BLOCK_PROFILEINDIVIDUAL_UPDATE:
                $ret[] = [GD_URE_Module_Processor_UpdateProfileDataloads::class, GD_URE_Module_Processor_UpdateProfileDataloads::COMPONENT_DATALOAD_PROFILEINDIVIDUAL_UPDATE];
                break;
        }
    
        return $ret;
    }
}



