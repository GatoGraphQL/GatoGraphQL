<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_AppendScriptsLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_APPENDSCRIPT];
    }

    public function doAppend(array $component)
    {

        // Through doAppend, we can have both success and conditionfailed layouts execute.
        // conditionfailed must also be executed just to remove the class to search for, eg: "pop-append-posts-334"
        return true;
    }

    // function stopAppending(array $component) {

    //     // Comments will not stop appending, everything else will
    //     return true;
    // }

    public function getLayoutSubmodule(array $component)
    {
        return null;
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        if ($this->doAppend($component)) {
            if ($layout = $this->getLayoutSubmodule($component)) {
                $ret[] = $layout;
            }
        }

        return $ret;
    }

    public function getOperation(array $component, array &$props)
    {
        return 'append';
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
    
        $ret = parent::getImmutableConfiguration($component, $props);

        // if ($this->stopAppending($component)) {
        
        //     $ret['stop-appending'] = true;
        // }

        if ($this->doAppend($component)) {
            $ret['do-append'] = true;
            $ret['frame-module'] = $this->getProp($component, $props, 'frame-module');
            $ret['operation'] = $this->getOperation($component, $props);

            if ($layout = $this->getLayoutSubmodule($component)) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layout'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($layout);
            }
        }
        
        return $ret;
    }
}
