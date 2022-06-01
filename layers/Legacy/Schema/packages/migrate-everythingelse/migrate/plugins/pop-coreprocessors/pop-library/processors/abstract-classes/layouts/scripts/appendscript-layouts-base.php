<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_AppendScriptsLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_APPENDSCRIPT];
    }

    public function doAppend(\PoP\ComponentModel\Component\Component $component)
    {

        // Through doAppend, we can have both success and conditionfailed layouts execute.
        // conditionfailed must also be executed just to remove the class to search for, eg: "pop-append-posts-334"
        return true;
    }

    // function stopAppending(\PoP\ComponentModel\Component\Component $component) {

    //     // Comments will not stop appending, everything else will
    //     return true;
    // }

    public function getLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($this->doAppend($component)) {
            if ($layout = $this->getLayoutSubcomponent($component)) {
                $ret[] = $layout;
            }
        }

        return $ret;
    }

    public function getOperation(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'append';
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
    
        $ret = parent::getImmutableConfiguration($component, $props);

        // if ($this->stopAppending($component)) {
        
        //     $ret['stop-appending'] = true;
        // }

        if ($this->doAppend($component)) {
            $ret['do-append'] = true;
            $ret['frame-component'] = $this->getProp($component, $props, 'frame-component');
            $ret['operation'] = $this->getOperation($component, $props);

            if ($layout = $this->getLayoutSubcomponent($component)) {
                $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['layout'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($layout);
            }
        }
        
        return $ret;
    }
}
