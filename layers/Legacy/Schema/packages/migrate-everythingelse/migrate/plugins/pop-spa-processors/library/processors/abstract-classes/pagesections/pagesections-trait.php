<?php

trait PoP_SPA_Module_Processor_PageSections_Trait
{
    public function isFrontendIdUnique(\PoP\ComponentModel\Component\Component $component, array &$props): bool
    {
        return true;
    }
    
    public function fixedId(\PoP\ComponentModel\Component\Component $component, array &$props): bool
    {
        return true;
    }

    public function getFrontendMergeid(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return $this->getFrontendId($component, $props);
    }
}
