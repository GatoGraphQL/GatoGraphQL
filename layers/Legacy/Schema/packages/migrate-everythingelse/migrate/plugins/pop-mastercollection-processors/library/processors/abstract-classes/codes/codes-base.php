<?php

abstract class PoP_Module_Processor_CodesBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getCode(array $componentVariation, array &$props)
    {
        return null;
    }

    protected function isStaticCode(array $componentVariation, array &$props)
    {
        return true;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);
    
        if ($this->isStaticCode($componentVariation, $props)) {
            $ret['code'] = $this->getCode($componentVariation, $props);
        }
        
        return $ret;
    }

    public function getMutableonrequestConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($componentVariation, $props);
    
        if (!$this->isStaticCode($componentVariation, $props)) {
            $ret['code'] = $this->getCode($componentVariation, $props);
        }
        
        return $ret;
    }
}
