<?php
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

abstract class PoP_Module_Processor_ScrollsBase extends PoP_Module_Processor_StructuresBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_SCROLL];
    }

    protected function getDescription(array $module, array &$props)
    {
        return null;
    }

    public function getFetchmoreButtonSubmodule(array $module)
    {
        return [PoP_Module_Processor_FetchMore::class, PoP_Module_Processor_FetchMore::MODULE_FETCHMORE];
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($fetchmore = $this->getFetchmoreButtonSubmodule($module)) {
            $ret[] = $fetchmore;
        }
                
        return $ret;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        if ($this->useFetchmore($module, $props)) {
            $fetchmore = $this->getFetchmoreButtonSubmodule($module);
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['fetchmore'] = ModuleUtils::getModuleOutputName($fetchmore);
        }
        if ($description = $this->getProp($module, $props, 'description')) {
            $ret[GD_JS_DESCRIPTION] = $description;
        }
        if ($inner_class = $this->getProp($module, $props, 'inner-class')) {
            $ret[GD_JS_CLASSES]['inner'] = $inner_class;
        }
        
        return $ret;
    }

    protected function useFetchmore(array $module, array &$props)
    {
        return $this->getFetchmoreButtonSubmodule($module) && $this->getProp($module, $props, 'show-fetchmore');
    }

    public function initModelProps(array $module, array &$props)
    {
        $this->setProp($module, $props, 'show-fetchmore', true);
        $this->setProp($module, $props, 'description', $this->getDescription($module, $props));
        parent::initModelProps($module, $props);
    }
}
