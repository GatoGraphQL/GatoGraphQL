<?php
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

abstract class PoP_Module_Processor_ContentLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_CONTENT];
    }
    
    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($abovecontent_modules = $this->getAbovecontentSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $abovecontent_modules
            );
        }

        return $ret;
    }

    public function getDataFields(array $module, array &$props): array
    {
        return array_merge(
            parent::getDataFields($module, $props),
            array(
                'content',
            )
        );
    }

    public function getAbovecontentSubmodules(array $module)
    {
        return array();
    }

    public function getContentMaxlength(array $module, array &$props)
    {
        return null;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        if ($this->getContentMaxlength($module, $props)) {
            $this->addJsmethod($ret, 'showmore', 'inner');
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($module, $props);

        if ($abovecontent_modules = $this->getAbovecontentSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['abovecontent'] = array_map(
                [ModuleUtils::class, 'getModuleOutputName'], 
                $abovecontent_modules
            );
        }

        if ($length = $this->getContentMaxlength($module, $props)) {
            $ret['content-maxlength'] = $length;
        }

        return $ret;
    }
}
