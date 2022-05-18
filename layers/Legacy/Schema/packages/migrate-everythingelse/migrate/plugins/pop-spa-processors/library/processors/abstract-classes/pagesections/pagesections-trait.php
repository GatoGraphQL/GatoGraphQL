<?php

trait PoP_SPA_Module_Processor_PageSections_Trait
{
    public function isFrontendIdUnique(array $componentVariation, array &$props): bool
    {
        return true;
    }
    
    public function fixedId(array $componentVariation, array &$props): bool
    {
        return true;
    }

    public function getFrontendMergeid(array $componentVariation, array &$props)
    {
        return $this->getFrontendId($componentVariation, $props);
    }
}
