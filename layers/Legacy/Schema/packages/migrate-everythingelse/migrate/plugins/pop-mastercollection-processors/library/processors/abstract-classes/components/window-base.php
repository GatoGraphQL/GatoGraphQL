<?php

abstract class PoP_Module_Processor_WindowBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_WINDOW];
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        $this->addJsmethod($ret, 'windowSize', 'fullsize');
        $this->addJsmethod($ret, 'windowSize', 'maximize');
        $this->addJsmethod($ret, 'windowSize', 'minimize');

        return $ret;
    }

    protected function getComponentClasses(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return array();
    }

    protected function getComponentParams(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return array();
    }

    protected function getWrapperClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return '';
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($wrapper_class = $this->getWrapperClass($component, $props)) {
            $ret[GD_JS_CLASSES]['wrapper'] = $wrapper_class;
        }

        if ($componentclasses = $this->getComponentClasses($component, $props)) {
            $ret['componentclasses'] = $componentclasses;
        }

        if ($componentparams = $this->getComponentParams($component, $props)) {
            $ret['componentparams'] = $componentparams;
        }
        
        if ($subcomponents = $this->getSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['elements'] = array_map(
                \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName(...), 
                $subcomponents
            );
        }
        
        return $ret;
    }
}
