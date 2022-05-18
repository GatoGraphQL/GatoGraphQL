<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_AppendScriptsLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_APPENDSCRIPT];
    }

    public function doAppend(array $module)
    {

        // Through doAppend, we can have both success and conditionfailed layouts execute.
        // conditionfailed must also be executed just to remove the class to search for, eg: "pop-append-posts-334"
        return true;
    }

    // function stopAppending(array $module) {

    //     // Comments will not stop appending, everything else will
    //     return true;
    // }

    public function getLayoutSubmodule(array $module)
    {
        return null;
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($this->doAppend($module)) {
            if ($layout = $this->getLayoutSubmodule($module)) {
                $ret[] = $layout;
            }
        }

        return $ret;
    }

    public function getOperation(array $module, array &$props)
    {
        return 'append';
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
    
        $ret = parent::getImmutableConfiguration($module, $props);

        // if ($this->stopAppending($module)) {
        
        //     $ret['stop-appending'] = true;
        // }

        if ($this->doAppend($module)) {
            $ret['do-append'] = true;
            $ret['frame-module'] = $this->getProp($module, $props, 'frame-module');
            $ret['operation'] = $this->getOperation($module, $props);

            if ($layout = $this->getLayoutSubmodule($module)) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layout'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($layout);
            }
        }
        
        return $ret;
    }
}
