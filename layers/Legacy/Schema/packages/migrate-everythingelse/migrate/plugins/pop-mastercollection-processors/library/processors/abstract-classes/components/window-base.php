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

    protected function getModuleParams(array $component, array &$props)
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

        if ($moduleclasses = $this->getModuleClasses($component, $props)) {
            $ret['moduleclasses'] = $moduleclasses;
        }

        if ($moduleparams = $this->getModuleParams($component, $props)) {
            $ret['moduleparams'] = $moduleparams;
        }
        
        if ($subComponents = $this->getSubComponents($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['elements'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'], 
                $subComponents
            );
        }
        
        return $ret;
    }
}
