<?php

abstract class PoP_Module_Processor_WindowBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_WINDOW];
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        $this->addJsmethod($ret, 'windowSize', 'fullsize');
        $this->addJsmethod($ret, 'windowSize', 'maximize');
        $this->addJsmethod($ret, 'windowSize', 'minimize');

        return $ret;
    }

    protected function getModuleClasses(array $module, array &$props)
    {
        return array();
    }

    protected function getModuleParams(array $module, array &$props)
    {
        return array();
    }

    protected function getWrapperClass(array $module, array &$props)
    {
        return '';
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($wrapper_class = $this->getWrapperClass($module, $props)) {
            $ret[GD_JS_CLASSES]['wrapper'] = $wrapper_class;
        }

        if ($moduleclasses = $this->getModuleClasses($module, $props)) {
            $ret['moduleclasses'] = $moduleclasses;
        }

        if ($moduleparams = $this->getModuleParams($module, $props)) {
            $ret['moduleparams'] = $moduleparams;
        }
        
        if ($submodules = $this->getSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['elements'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'], 
                $submodules
            );
        }
        
        return $ret;
    }
}
