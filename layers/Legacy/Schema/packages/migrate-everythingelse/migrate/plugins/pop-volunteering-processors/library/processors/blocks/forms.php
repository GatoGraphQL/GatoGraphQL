<?php

class PoP_Volunteering_Module_Processor_Blocks extends PoP_Module_Processor_FormBlocksBase
{
    public final const MODULE_BLOCK_VOLUNTEER = 'block-volunteer';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_VOLUNTEER],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_BLOCK_VOLUNTEER => POP_VOLUNTEERING_ROUTE_VOLUNTEER,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_VOLUNTEER:
                $ret[] = [PoP_Volunteering_Module_Processor_Dataloads::class, PoP_Volunteering_Module_Processor_Dataloads::MODULE_DATALOAD_VOLUNTEER];
                break;
        }
    
        return $ret;
    }
}



