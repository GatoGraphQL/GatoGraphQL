<?php

use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;

class PoP_UserAvatarProcessors_Module_Processor_UserBlocks extends PoP_Module_Processor_BlocksBase
{
    public final const MODULE_BLOCK_USERAVATAR_UPDATE = 'block-useravatar-update';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_USERAVATAR_UPDATE],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_BLOCK_USERAVATAR_UPDATE => POP_USERAVATAR_ROUTE_EDITAVATAR,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_USERAVATAR_UPDATE:
                // Either with or without moduleAtts
                $pop_componentVariation_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();
                $ret[] = $pop_componentVariation_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUPPLACEHOLDER_MAINCONTENTMODULE);
                break;
        }
    
        return $ret;
    }
}



