<?php

abstract class PoP_Module_Processor_MenuLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getDataFields(array $module, array &$props): array
    {
        return array('id', 'items');
    }

    public function getItemClass(array $module, array &$props)
    {
        return '';
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret[GD_JS_CLASSES]['item'] = $this->getItemClass($module, $props);
        
        return $ret;
    }
}
