<?php

abstract class PoP_Module_Processor_BootstrapComponentsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getBootstrapcomponentClass(array $module)
    {

        // Needed for all the hooks using Bootstrap (show.bs.modal, etc)
        return 'pop-bscomponent';
    }

    public function getContainerClass(array $module)
    {
        return '';
    }

    public function getBootstrapcomponentType(array $module)
    {
        return '';
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($component_class = $this->getBootstrapcomponentClass($module)) {
            $ret[GD_JS_CLASSES]['bootstrap-component'] = $component_class;
        }
        if ($container_class = $this->getContainerClass($module)) {
            $ret[GD_JS_CLASSES]['container'] = $container_class;
        }
        if ($component_type = $this->getBootstrapcomponentType($module)) {
            $ret['bootstrap-type'] = $component_type;
        }
                
        return $ret;
    }

    protected function getInitjsBlockbranches(array $module, array &$props)
    {
        return array();
    }
    
    public function getMutableonrequestJsconfiguration(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestJsconfiguration($module, $props);

        if ($branches = $this->getInitjsBlockbranches($module, $props)) {
            $ret['initActiveBranchesJSMethods']['initjs-blockbranches'] = $branches;
        }

        return $ret;
    }

    public function getInitializationjsmethod(array $module, array &$props)
    {
        return 'initActiveBranchesJSMethods';
    }
}
