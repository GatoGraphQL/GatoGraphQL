<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_PopoverLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_POPOVER];
    }

    public function getLayoutSubmodule(array $component)
    {
        return null;
    }
    public function getLayoutContentSubmodule(array $component)
    {
        return null;
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($layout = $this->getLayoutSubmodule($component)) {
            $ret[] = $layout;
        }
        if ($layout_content = $this->getLayoutContentSubmodule($component)) {
            $ret[] = $layout_content;
        }

        return $ret;
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);
        $this->addJsmethod($ret, 'popover');
        return $ret;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($layout = $this->getLayoutSubmodule($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layout'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($layout);
        }
        if ($layout_content = $this->getLayoutContentSubmodule($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layout-content'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($layout_content);
        }
        
        return $ret;
    }
}
