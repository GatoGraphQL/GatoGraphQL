<?php

class PoP_ContactUs_Module_Processor_Blocks extends PoP_Module_Processor_FormBlocksBase
{
    public final const COMPONENT_BLOCK_CONTACTUS = 'block-contactus';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BLOCK_CONTACTUS,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_BLOCK_CONTACTUS => POP_CONTACTUS_ROUTE_CONTACTUS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_BLOCK_CONTACTUS:
                $ret[] = [PoP_ContactUs_Module_Processor_Dataloads::class, PoP_ContactUs_Module_Processor_Dataloads::COMPONENT_DATALOAD_CONTACTUS];
                break;
        }
    
        return $ret;
    }
}


