<?php

abstract class PoP_Module_Processor_BootstrapComponentsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getBootstrapcomponentClass(array $componentVariation)
    {

        // Needed for all the hooks using Bootstrap (show.bs.modal, etc)
        return 'pop-bscomponent';
    }

    public function getContainerClass(array $componentVariation)
    {
        return '';
    }

    public function getBootstrapcomponentType(array $componentVariation)
    {
        return '';
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($component_class = $this->getBootstrapcomponentClass($componentVariation)) {
            $ret[GD_JS_CLASSES]['bootstrap-component'] = $component_class;
        }
        if ($container_class = $this->getContainerClass($componentVariation)) {
            $ret[GD_JS_CLASSES]['container'] = $container_class;
        }
        if ($component_type = $this->getBootstrapcomponentType($componentVariation)) {
            $ret['bootstrap-type'] = $component_type;
        }
                
        return $ret;
    }

    protected function getInitjsBlockbranches(array $componentVariation, array &$props)
    {
        return array();
    }
    
    public function getMutableonrequestJsconfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonrequestJsconfiguration($componentVariation, $props);

        if ($branches = $this->getInitjsBlockbranches($componentVariation, $props)) {
            $ret['initActiveBranchesJSMethods']['initjs-blockbranches'] = $branches;
        }

        return $ret;
    }

    public function getInitializationjsmethod(array $componentVariation, array &$props)
    {
        return 'initActiveBranchesJSMethods';
    }
}
