<?php

use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;

class PoP_UserAvatarProcessors_Module_Processor_UserBlocks extends PoP_Module_Processor_BlocksBase
{
    public final const MODULE_BLOCK_USERAVATAR_UPDATE = 'block-useravatar-update';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_USERAVATAR_UPDATE],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_USERAVATAR_UPDATE => POP_USERAVATAR_ROUTE_EDITAVATAR,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_BLOCK_USERAVATAR_UPDATE:
                // Either with or without componentAtts
                $pop_component_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();
                $ret[] = $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUPPLACEHOLDER_MAINCONTENTMODULE);
                break;
        }
    
        return $ret;
    }
}



