<?php

class PoP_ContactUs_Module_Processor_Blocks extends PoP_Module_Processor_FormBlocksBase
{
    public final const MODULE_BLOCK_CONTACTUS = 'block-contactus';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_CONTACTUS],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_CONTACTUS => POP_CONTACTUS_ROUTE_CONTACTUS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_BLOCK_CONTACTUS:
                $ret[] = [PoP_ContactUs_Module_Processor_Dataloads::class, PoP_ContactUs_Module_Processor_Dataloads::COMPONENT_DATALOAD_CONTACTUS];
                break;
        }
    
        return $ret;
    }
}


