<?php

abstract class PoP_Module_Processor_CodesBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getCode(array $component, array &$props)
    {
        return null;
    }

    protected function isStaticCode(array $component, array &$props)
    {
        return true;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);
    
        if ($this->isStaticCode($component, $props)) {
            $ret['code'] = $this->getCode($component, $props);
        }
        
        return $ret;
    }

    public function getMutableonrequestConfiguration(array $component, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($component, $props);
    
        if (!$this->isStaticCode($component, $props)) {
            $ret['code'] = $this->getCode($component, $props);
        }
        
        return $ret;
    }
}
