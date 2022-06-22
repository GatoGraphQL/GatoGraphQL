<?php

abstract class PoP_Module_Processor_BootstrapComponentsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getBootstrapcomponentClass(\PoP\ComponentModel\Component\Component $component)
    {

        // Needed for all the hooks using Bootstrap (show.bs.modal, etc)
        return 'pop-bscomponent';
    }

    public function getContainerClass(\PoP\ComponentModel\Component\Component $component)
    {
        return '';
    }

    public function getBootstrapcomponentType(\PoP\ComponentModel\Component\Component $component)
    {
        return '';
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($component_class = $this->getBootstrapcomponentClass($component)) {
            $ret[GD_JS_CLASSES]['bootstrap-component'] = $component_class;
        }
        if ($container_class = $this->getContainerClass($component)) {
            $ret[GD_JS_CLASSES]['container'] = $container_class;
        }
        if ($component_type = $this->getBootstrapcomponentType($component)) {
            $ret['bootstrap-type'] = $component_type;
        }
                
        return $ret;
    }

    protected function getInitjsBlockbranches(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return array();
    }
    
    public function getMutableonrequestJsconfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getMutableonrequestJsconfiguration($component, $props);

        if ($branches = $this->getInitjsBlockbranches($component, $props)) {
            $ret['initActiveBranchesJSMethods']['initjs-blockbranches'] = $branches;
        }

        return $ret;
    }

    public function getInitializationjsmethod(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'initActiveBranchesJSMethods';
    }
}
