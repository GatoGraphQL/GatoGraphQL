<?php

abstract class PoP_Module_Processor_CodesBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getCode(array $module, array &$props)
    {
        return null;
    }

    protected function isStaticCode(array $module, array &$props)
    {
        return true;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);
    
        if ($this->isStaticCode($module, $props)) {
            $ret['code'] = $this->getCode($module, $props);
        }
        
        return $ret;
    }

    public function getMutableonrequestConfiguration(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($module, $props);
    
        if (!$this->isStaticCode($module, $props)) {
            $ret['code'] = $this->getCode($module, $props);
        }
        
        return $ret;
    }
}
