<?php

class PoP_ContactUs_Module_Processor_Blocks extends PoP_Module_Processor_FormBlocksBase
{
    public final const MODULE_BLOCK_CONTACTUS = 'block-contactus';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_CONTACTUS],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_BLOCK_CONTACTUS => POP_CONTACTUS_ROUTE_CONTACTUS,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_CONTACTUS:
                $ret[] = [PoP_ContactUs_Module_Processor_Dataloads::class, PoP_ContactUs_Module_Processor_Dataloads::MODULE_DATALOAD_CONTACTUS];
                break;
        }
    
        return $ret;
    }
}


