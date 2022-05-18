<?php

abstract class PoP_Module_Processor_WindowBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_WINDOW];
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        $this->addJsmethod($ret, 'windowSize', 'fullsize');
        $this->addJsmethod($ret, 'windowSize', 'maximize');
        $this->addJsmethod($ret, 'windowSize', 'minimize');

        return $ret;
    }

    protected function getModuleClasses(array $componentVariation, array &$props)
    {
        return array();
    }

    protected function getModuleParams(array $componentVariation, array &$props)
    {
        return array();
    }

    protected function getWrapperClass(array $componentVariation, array &$props)
    {
        return '';
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($wrapper_class = $this->getWrapperClass($componentVariation, $props)) {
            $ret[GD_JS_CLASSES]['wrapper'] = $wrapper_class;
        }

        if ($moduleclasses = $this->getModuleClasses($componentVariation, $props)) {
            $ret['moduleclasses'] = $moduleclasses;
        }

        if ($moduleparams = $this->getModuleParams($componentVariation, $props)) {
            $ret['moduleparams'] = $moduleparams;
        }
        
        if ($subComponentVariations = $this->getSubComponentVariations($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['elements'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'], 
                $subComponentVariations
            );
        }
        
        return $ret;
    }
}
