<?php

trait PoP_SPA_Module_Processor_PageSections_Trait
{
    public function isFrontendIdUnique(array $component, array &$props): bool
    {
        return true;
    }
    
    public function fixedId(array $component, array &$props): bool
    {
        return true;
    }

    public function getFrontendMergeid(array $component, array &$props)
    {
        return $this->getFrontendId($component, $props);
    }
}
