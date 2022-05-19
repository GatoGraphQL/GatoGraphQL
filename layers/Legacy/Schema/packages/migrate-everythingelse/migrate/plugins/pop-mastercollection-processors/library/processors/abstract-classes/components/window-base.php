<?php

abstract class PoP_Module_Processor_WindowBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_WINDOW];
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        $this->addJsmethod($ret, 'windowSize', 'fullsize');
        $this->addJsmethod($ret, 'windowSize', 'maximize');
        $this->addJsmethod($ret, 'windowSize', 'minimize');

        return $ret;
    }

    protected function getModuleClasses(array $component, array &$props)
    {
        return array();
    }

    protected function getComponentParams(array $component, array &$props)
    {
        return array();
    }

    protected function getWrapperClass(array $component, array &$props)
    {
        return '';
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($wrapper_class = $this->getWrapperClass($component, $props)) {
            $ret[GD_JS_CLASSES]['wrapper'] = $wrapper_class;
        }

        if ($componentclasses = $this->getModuleClasses($component, $props)) {
            $ret['componentclasses'] = $componentclasses;
        }

        if ($componentparams = $this->getComponentParams($component, $props)) {
            $ret['componentparams'] = $componentparams;
        }
        
        if ($subComponents = $this->getSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['elements'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance(), 'getComponentOutputName'], 
                $subComponents
            );
        }
        
        return $ret;
    }
}
