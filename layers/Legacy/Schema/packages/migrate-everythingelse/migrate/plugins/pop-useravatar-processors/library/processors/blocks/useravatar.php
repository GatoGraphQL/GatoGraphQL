<?php

use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;

class PoP_UserAvatarProcessors_Module_Processor_UserBlocks extends PoP_Module_Processor_BlocksBase
{
    public final const COMPONENT_BLOCK_USERAVATAR_UPDATE = 'block-useravatar-update';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_USERAVATAR_UPDATE],
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_BLOCK_USERAVATAR_UPDATE => POP_USERAVATAR_ROUTE_EDITAVATAR,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_BLOCK_USERAVATAR_UPDATE:
                // Either with or without componentAtts
                $pop_component_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();
                $ret[] = $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGECOMPONENTGROUPPLACEHOLDER_MAINCONTENTCOMPONENT);
                break;
        }
    
        return $ret;
    }
}



