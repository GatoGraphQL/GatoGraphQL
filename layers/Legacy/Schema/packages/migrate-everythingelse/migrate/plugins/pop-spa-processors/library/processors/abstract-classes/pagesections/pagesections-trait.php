<?php

trait PoP_SPA_Module_Processor_PageSections_Trait
{
    public function isFrontendIdUnique(array $module, array &$props): bool
    {
        return true;
    }
    
    public function fixedId(array $module, array &$props): bool
    {
        return true;
    }

    public function getFrontendMergeid(array $module, array &$props)
    {
        return $this->getFrontendId($module, $props);
    }
}
