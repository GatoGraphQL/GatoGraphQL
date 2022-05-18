<?php

class PoP_Volunteering_Module_Processor_Blocks extends PoP_Module_Processor_FormBlocksBase
{
    public final const COMPONENT_BLOCK_VOLUNTEER = 'block-volunteer';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_VOLUNTEER],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_VOLUNTEER => POP_VOLUNTEERING_ROUTE_VOLUNTEER,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_BLOCK_VOLUNTEER:
                $ret[] = [PoP_Volunteering_Module_Processor_Dataloads::class, PoP_Volunteering_Module_Processor_Dataloads::COMPONENT_DATALOAD_VOLUNTEER];
                break;
        }
    
        return $ret;
    }
}



