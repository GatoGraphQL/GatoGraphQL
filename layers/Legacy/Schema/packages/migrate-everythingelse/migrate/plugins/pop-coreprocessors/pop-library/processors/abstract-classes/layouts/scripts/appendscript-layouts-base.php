<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_AppendScriptsLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_APPENDSCRIPT];
    }

    public function doAppend(array $componentVariation)
    {

        // Through doAppend, we can have both success and conditionfailed layouts execute.
        // conditionfailed must also be executed just to remove the class to search for, eg: "pop-append-posts-334"
        return true;
    }

    // function stopAppending(array $componentVariation) {

    //     // Comments will not stop appending, everything else will
    //     return true;
    // }

    public function getLayoutSubmodule(array $componentVariation)
    {
        return null;
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        if ($this->doAppend($componentVariation)) {
            if ($layout = $this->getLayoutSubmodule($componentVariation)) {
                $ret[] = $layout;
            }
        }

        return $ret;
    }

    public function getOperation(array $componentVariation, array &$props)
    {
        return 'append';
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
    
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        // if ($this->stopAppending($componentVariation)) {
        
        //     $ret['stop-appending'] = true;
        // }

        if ($this->doAppend($componentVariation)) {
            $ret['do-append'] = true;
            $ret['frame-module'] = $this->getProp($componentVariation, $props, 'frame-module');
            $ret['operation'] = $this->getOperation($componentVariation, $props);

            if ($layout = $this->getLayoutSubmodule($componentVariation)) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layout'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($layout);
            }
        }
        
        return $ret;
    }
}
